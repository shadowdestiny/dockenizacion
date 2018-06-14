<?php

namespace EuroMillions\web\pdf;

class GDPRPdfTemplate extends \EuroMillions\web\components\PdfTemplateDecorator
{

    protected $data;

    public function loadBody()
    {
        if(is_array($this->data)) {
            $details = $this->data['user_details'];
            $notifications = $this->data['notifications'];
            $transactions = $this->data['transactions'];
            $tickets =  count($this->data['tickets']) > 0 ? $this->data['tickets']->result : [];
            $lastTickets = is_array($this->data['last_tickets']) ? [] : $this->data['last_tickets']->result;
            $subscriptions = count($this->data['subscriptions']) > 0 ? $this->data['subscriptions'] : [];
            $inactiveSubscription = count($this->data['inactive_subscriptions']) > 0 ? $this->data['inactive_subscriptions'] : [];

            $html = '<html>
<head></head>
<body>
<table border="0">
<tr>
<td>
' . $this->loadHeader() . '
</td>
</tr>
</table>
<table border="1">
<tr>
<th>Name</th>
<th>Username</th>
<th>Email</th>
<th>Country</th>
<th>Street</th>
<th>Zip</th>
<th>City</th>
<th>Phone</th>
</tr>
<tr>
<td>'.$details['name'].'</td>
<td>'.$details['surname'].'</td>
<td>'.$details['email'].'</td>
<td>'.$details['country'].'</td>
<td>'.$details['street'].'</td>
<td>'.$details['zip'].'</td>
<td>'.$details['city'].'</td>
<td>'.$details['phone'].'</td>
</tr>
</table>
<br>
<p>
 Email Settings
</p>
<table border="1">
<tr>
<th>Description</th>
<th>Active</th>
</tr>
<tr>
<td>'.$notifications[0]->notification->description.'</td>
<td>'.$notifications[0]->active.'</td>
</tr>
<tr>
<td>'.$notifications[3]->notification->description.'</td>
<td>'.$notifications[3]->active.'</td>
</tr>
<tr>
<td>'.$notifications[4]->notification->description.'</td>
<td>'.$notifications[4]->active.'</td>
</tr>
</table>

<br>
<br>
<p>Subscriptions</p>
<table border="1">
<tr>
<th>Start date</th>
<th>End date</th>
<th>Numbers played</th>
</tr>';
if(count($subscriptions) > 0 ) {
    foreach ($subscriptions as $subscription) {
        $startDate = $subscription['start_draw_date'];
        $lastDate = $subscription['last_draw_date'];
        $html .= '<tr><td>' . $startDate->format('Y-m-d') . '</td>' . '<td>'.$lastDate->format('Y-m-d') .'</td>';
        unset($subscription['start_draw_date']);
        unset($subscription['last_draw_date']);
        unset($subscription['lines']);
        $html .='<td>';
        foreach ($subscription as $playConfigs) {
            foreach ($playConfigs as $numbers ) {
                $html .=  $numbers . ', ';
            }
            $html .= '<br>';

        }
        $html .= '</td></tr>';
    }
} else {
    $html .= '<tr><td></td><td></td><td></td></tr>';
}
$html .= '
</table>

<br>
<br>

<p> Inactive Subscriptions</p>
<table border="1">
<tr>
<th>Start date</th>
<th>End date</th>
<th>Numbers played</th>
</tr>';
if(count($inactiveSubscription) > 0 ) {
    foreach ($inactiveSubscription as $subscription) {
        $startDate = $subscription['start_draw_date'];
        $lastDate = $subscription['last_draw_date'];
        $html .= '<tr><td>' . $startDate->format('Y-m-d') . '</td>' . '<td>'.$lastDate->format('Y-m-d') .'</td>';
        unset($subscription['start_draw_date']);
        unset($subscription['last_draw_date']);
        unset($subscription['lines']);
        $html .='<td>';
        foreach ($subscription as $playConfigs) {
            foreach ($playConfigs as $numbers ) {
                $html .=  $numbers . ', ';
            }
            $html .= '<br>';

        }
        $html .= '</td></tr>';
    }
} else {
    $html .= '<tr><td></td><td></td><td></td></tr>';
}

$html .='
</table>
<br>
<br>
<p> Upcoming Draws</p>
<table border="1">
<tr>
<th>Draw date</th>
<th>Numbers played</th>
</tr>';
if(count($tickets) > 0 ) {
    foreach ($tickets as $k => $v) {
        $html .= '<tr><td>'.$k.'</td><td>';
        foreach ($v as $numbers) {
            $number = json_decode($numbers->lines);
            $html .= $number->bets[0]->regular . ' ' . $number->bets[0]->lucky . '<br>';
        }
        $html .= '</td></tr>';
    }
} else {
    $html .= '<tr><td></td><td></td></tr>';
}
$html .= '
</table>
<br>
<br>
<p> Past Draws</p>
<table border="1">
<tr>
<th>Draw date</th>
<th>Numbers played</th>
</tr>';
if( count($lastTickets) > 0 ) {
    foreach ($lastTickets['dates'] as $d => $draws) {
        $html .= '<tr><td>'.$d.'</td><td>';
        foreach ($draws as $numbers) {
            $html .= implode(',',array_keys($numbers->numbers)). ' ' . implode(',',array_keys($numbers->stars)) . '<br>';
        }
        $html .= '</td></tr>';
    }
} else {
    $html .= '<tr><td></td><td></td></tr>';
}
$html .= '
</table>
<br><br>
<p> Transactions</p>
<table border="1">
<tr>
<th> Date </th>
<th>Transaction</th>
<th> Amount </th>
</tr>';
if( count($transactions) > 0 ) {
    foreach ($transactions as $transaction ) {
        $html .= '<tr><td>'.$transaction->date->format('Y/m/d') .'</td><td>'. $transaction->transactionName .'</td><td>'.$transaction->movement .' </td></tr>';
    }
} else {
    $html .= '<tr><td></td><td></td><td></td></tr>';
}
$html .= '
</table>
</body>
</html>';


            return $html;
        }
    }

    public function loadHeader()
    {
        return '<img width="350px" height="53px" src="https://www.euromillions.com/w/img/logo/v2/logo-desktop.png" alt="Euromillions">';
        //$this->pdfTemplate->loadHeader();
    }

    public function loadFooter()
    {
        $this->pdfTemplate->loadFooter();
    }
    public function setData($data)
    {
        $this->data = $data;
    }

}