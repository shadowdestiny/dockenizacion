<?php

namespace EuroMillions\shared\tasks;

use EuroMillions\web\tasks\TaskBase;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class DumpTranslationsTask extends TaskBase
{
    private $allowedEnvsExport = array('beta');
    private $allowedEnvsImport = array('development');
    private $bucketName = 'euromillions-dumps';

    public function mainAction()
    {
        if(!in_array(getenv('EM_ENV'), $this->allowedEnvsExport )){
            echo "Not allowed from this Environment\n";
            return 1;
        }

        $config = $this->getDatabaseConfig();
        $tables = "languages translation_categories translations translation_details"; //List of tables to dump
        $dump_file = "/tmp/".$config['database']."_translations.sql.gz";

        $command = "mysqldump --opt --default-character-set ".$config['charset']." --add-drop-table -h ".$config['host']." -u ".$config['user']." -p".$config['password']." ".$config['database']." $tables | gzip > $dump_file";

        system($command);

        if(file_exists($dump_file) && filesize($dump_file) > 0) {
            $this->uploadToS3($dump_file);
            unlink($dump_file);
        }

        return 0;
    }


    public function importAction()
    {
        if(!in_array(getenv('EM_ENV'), $this->allowedEnvsImport )){
            echo "Not allowed from this Environment\n";
            return 1;
        }

        $config = $this->getDatabaseConfig();

        $this->getFromS3('euromillions_translations.sql.gz');
        $dump_file = "/tmp/".$config['database']."_translations.sql.gz";

        if(file_exists($dump_file) && filesize($dump_file) > 0) {
            $command = "gunzip < $dump_file | mysql --default-character-set ".$config['charset']." -h ".$config['host']." -u ".$config['user']." -p".$config['password']." ".$config['database']."";
            system($command);
            unlink($dump_file);
        }

        return 0;
    }


    private function getFromS3($key)
    {
        $s3Client = null;

        try {
            $s3Client = $this->getS3Client();

            //Get
            $s3Client->getObject([
                'Bucket' => $this->bucketName,
                'Key' => $key,
                'SaveAs' => '/tmp/'.$key
            ]);

        } catch (AwsException $e) {
            echo $e->getMessage() . "\n";
        }
    }

    private function uploadToS3($filename)
    {
        $s3Client = null;

        try {
            $s3Client = $this->getS3Client();

            //Upload
            $s3Client->putObject([
                'Bucket' => $this->bucketName,
                'Key' => 'euromillions_translations.sql.gz',
                'SourceFile' => $filename,
            ]);

        } catch (AwsException $e) {
            echo $e->getMessage() . "\n";
        }
    }

    private function getS3Client()
    {
        return $s3Client = new S3Client([
            'region'            => 'eu-west-1',
            'version'           => '2006-03-01',
            'signature_version' => 'v4'
        ]);
    }

    private function getDatabaseConfig()
    {
        $appConfig = $this->di->get('config');
        return [
            'host' => $appConfig['database']['host'],
            'user' => $appConfig['database']['username'],
            'password' => $appConfig['database']['password'],
            'database' => $appConfig['database']['dbname'],
            'charset' => 'utf8',
        ];
    }
}