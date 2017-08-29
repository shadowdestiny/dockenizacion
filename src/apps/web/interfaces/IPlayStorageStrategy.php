<?php


namespace EuroMillions\web\interfaces;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\PlayFormToStorage;


interface IPlayStorageStrategy
{
    /** @return ActionResult */
    public function save($json, $userId);


    /**
     * @param $christmasTickets
     * @param $userId
     * @return ActionResult
     */
    public function saveChristmas($christmasTickets, $userId);

    /**
     * @param PlayFormToStorage $data
     * @param string $userId
     * @return ActionResult
     */
    public function saveAll(PlayFormToStorage $data, $userId);

    /**
     * @param string $key
     * @return ActionResult
     */
    public function findByKey($key);

    /**
     * @param $key
     * @return ActionResult
     */
    public function findByChristmasKey($key);

    /** @return void */
    public function delete($key = '');

}