<?php


namespace EuroMillions\web\forms\elements;


use Phalcon\Forms\Element;

class CreditCardExpiryDateElement extends Element
{

    /**
     * Renders the element widget
     *
     * @param array $attributes
     * @return string
     */
    public function render($attributes = null)
    {
        $html = '<select class="select month'.$attributes['class'].'" name="month">
                <option>01</option>
                <option>02</option>
                <option>03</option>
                <option>04</option>
                <option>05</option>
                <option>06</option>
                <option>07</option>
                <option>08</option>
                <option>09</option>
                <option>10</option>
                <option>11</option>
                <option>12</option>
            </select>';

         $html .= '<select class="select year '.$attributes['class'].'" name="year">
                <option>2016</option>
                <option>2017</option>
                <option>2018</option>
                <option>2019</option>
                <option>2020</option>
                <option>2021</option>
                <option>2022</option>
                <option>2023</option>
                <option>2024</option>
                <option>2025</option>
            </select>';

        return $html;
    }
}