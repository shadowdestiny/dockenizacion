<?php


namespace EuroMillions\admin\controllers;

use EuroMillions\admin\vo\ActionResult;
use EuroMillions\admin\vo\dto\DrawDTO;
use EuroMillions\admin\services\MaintenanceDrawService;
use EuroMillions\sharecomponents\widgets\PaginationWidget;
use EuroMillions\web\entities\EuroMillionsDraw;
use Money\Currency;
use Money\Money;

class DrawsController extends AdminControllerBase
{

    /** @var  MaintenanceDrawService */
    private $maintenanceDrawService;

    CONST LIMIT = 1;

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
        $page = (!empty($this->request->getQuery('page'))) ? $this->request->getQuery('page') : 1;
        $paginator = $this->getPaginatorAsArray($list_draws_dto,self::LIMIT,$page);
        /** @var \Phalcon\Mvc\ViewInterface $paginator_view */
        $paginator_view = (new PaginationWidget($paginator, $this->request->getQuery()))->render();

        return $this->view->setVars([
            'draws' => $paginator->getPaginate(),
            'paginator_view' => $paginator_view
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
                              'value' =>$draw_dto->toArray()
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
            $jackpot_value = new Money((int) $this->request->getPost('jackpot')*100, new Currency('EUR'));
            /** @var ActionResult $result */
            $result = $this->maintenanceDrawService->updateLastResult($regular_numbers,$lucky_numbers,$jackpot_value,$id_draw_to_edit);
            $result_draws = $this->maintenanceDrawService->getAllDrawsByLottery('EuroMillions');
            $list_draws_dto = [];
            if($result_draws->success()){
                foreach($result_draws->getValues() as $draw) {
                    $list_draws_dto[] = new DrawDTO($draw);
                }
            }
            $page = (!empty($this->request->getQuery('page'))) ? $this->request->getQuery('page') : 1;
            $paginator = $this->getPaginatorAsArray($list_draws_dto,self::LIMIT,$page);

            if($result->success()){
                echo json_encode(['result' => 'OK',
                                  'value'  => $paginator->getPaginate()->items
                ]);
            } else{
                echo json_encode(['result' => 'KO',
                                  'value'  => $paginator->getPaginate()->items
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