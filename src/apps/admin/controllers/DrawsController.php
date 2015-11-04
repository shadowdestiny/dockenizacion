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

    public function editAction()
    {
        $this->noRender();
        $id = $this->request->getPost('id');
        $id_draw_to_edit = $this->request->getPost('id_draw');
        if(!empty($id_draw_to_edit)){

            $regular_numbers = array_map('intval', explode(',', $this->request->getPost('numbers')));
            $lucky_numbers = array_map('intval', explode(',', $this->request->getPost('stars')));
            $jackpot_value = new Money((int) $this->request->getPost('jackpot') * 100, new Currency('EUR'));

            /** @var ActionResult $result */
            $result = $this->maintenanceDrawService->updateLastResult($regular_numbers,$lucky_numbers,$jackpot_value,$id_draw_to_edit);
            if($result->success()){
                echo json_encode(['message' => [
                                                    'OK' => ''
                                                ]
                ]);
            } else{
                echo json_encode(['message' => [
                                                    'KO' => ''
                                                ]
                ]);
            }
        }else{
            $result = $this->maintenanceDrawService->getDrawById($id);
            if($result->success()) {
                /** @var DrawDTO $draw_dto */
                $draw_dto = new DrawDTO($result->getValues());
                $draw_dto->setRegularNumbers(implode(',',$draw_dto->getRegularNumbers()));
                $draw_dto->setLuckyNumbers(implode(',',$draw_dto->getLuckyNumbers()));
                echo json_encode(['result'=> [
                        'OK' => $draw_dto->toArray()
                    ]
                ]);
            }else{
                echo json_encode(['result'=> [
                        'KO' => $result->errorMessage()
                    ]
                ]);
            }
        }
    }

}