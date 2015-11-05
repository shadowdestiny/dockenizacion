<?php


namespace EuroMillions\admin\controllers;

use EuroMillions\admin\vo\ActionResult;
use EuroMillions\admin\vo\dto\DrawDTO;
use EuroMillions\admin\services\MaintenanceDrawService;
use EuroMillions\web\entities\EuroMillionsDraw;
use Money\Currency;
use Money\Money;

class DrawsController extends AdminControllerBase
{

    /** @var  MaintenanceDrawService */
    private $maintenanceDrawService;


    public function initialize()
    {
        parent::initialize();
        $this->maintenanceDrawService = $this->domainAdminServiceFactory->getMaintenanceDrawService();
    }

    public function indexAction()
    {
        $result = $this->maintenanceDrawService->getAllDrawsByLottery('EuroMillions');
        $list_draws_dto = [];
        if($result->success()) {
            /** @var EuroMillionsDraw[] $list_draws */
            $list_draws = $result->getValues();
            foreach($list_draws as $draw){
                $list_draws_dto[] = new DrawDTO($draw);
            }
        }

        return $this->view->setVars([
            'draws' => $list_draws_dto
        ]);
    }

    public function viewAction()
    {
        $id = $this->request->getPost('id');
        $result = $this->maintenanceDrawService->getDrawById($id);
        if($result->success()) {
            /** @var DrawDTO $draw_dto */
            $draw_dto = new DrawDTO($result->getValues());
            $draw_dto->setRegularNumbers(implode(',',$draw_dto->getRegularNumbers()));
            $draw_dto->setLuckyNumbers(implode(',',$draw_dto->getLuckyNumbers()));
            echo json_encode(['result'=> 'OK',
                              'value' => $draw_dto->toArray()
            ]);
        }else{
            echo json_encode(['result'=> 'KO',
                              'value' => $result->errorMessage()
            ]);
        }
    }

    public function editAction()
    {
        $this->noRender();
        $id_draw_to_edit = $this->request->getPost('id_draw');
        if(!empty($id_draw_to_edit)){
            $regular_numbers = array_map('intval', explode(',', $this->request->getPost('numbers')));
            $lucky_numbers = array_map('intval', explode(',', $this->request->getPost('stars')));
            $jackpot_value = new Money((int) $this->request->getPost('jackpot'), new Currency('EUR'));
            /** @var ActionResult $result */
            $result = $this->maintenanceDrawService->updateLastResult($regular_numbers,$lucky_numbers,$jackpot_value,$id_draw_to_edit);
            if($result->success()){
                echo json_encode(['result' => 'OK',
                ]);
            } else{
                echo json_encode(['result' => 'KO',

                ]);
            }
        }
    }

    public function searchAction()
    {
        $this->noRender();
        $date = $this->request->getPost('date');

        if(!empty($date)) {
            try{
                $new_date = new \DateTime($date);
                $result = $this->maintenanceDrawService->getDrawByDate($new_date);
                if($result->success()){
                    /** @var EuroMillionsDraw $draw */
                    $draw = $result->getValues();
                    /** @var DrawDTO $draw_dto */
                    $draw_dto = new DrawDTO($draw);
                    echo json_encode([
                            'result' => 'OK',
                            'value'  => $draw_dto->toArray()
                    ]);
                }else {
                    echo json_encode([
                            'result' => 'KO'
                    ]);
                }
            }catch(\Exception $e) {
                    echo json_encode([
                            'result' => 'KO'
                    ]);
            }
        }else {
            echo json_encode([
                        'result' => 'KO'
            ]);
        }
    }

}