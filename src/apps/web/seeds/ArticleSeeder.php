<?php

use Phinx\Seed\AbstractSeed;

class ArticleSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $currencies = $this->table('articles');
        $currencies->insert($this->getData())
            ->save();
    }

    private function getData()
    {
        return [
            [
                'id' => 1,
                'content' => '<p align="justify">To <strong>win the Euromillions jackpot prize</strong>, players need to match 5 main numbers from 50, and 2 lucky stars from 11. The Euromillions odds of this actually happening is approximately 116,531,800 to 1. Although the <strong>odds of winning the Euromillions lottery</strong> may appear very slim, these odds (116,531,800 to 1) actually refer to the odds of winning the Euromillions jackpot in one particular draw. In addition, the <a href="{{base_url}}/en">Euromillions lottery</a> features 13 different tiers thus, the <strong>odds of a Euromillions win</strong> is approximately <strong>1 in 13</strong>.
<p align="justify">Finally, and more encouragingly, the more than generous <strong>Euromillions jackpot prizes</strong> can reach as much as €190 million with a guaranteed jackpot of at least €15 million (£12 million) per draw. In the absence of a first prize winner, the money is rolled over to the next draw which will grow in successive categories until either a <strong>Euromillions jackpot winner</strong> is produced, or the <strong>Euromillions Pool Cap</strong> (190 million euro) is reached.</p>
<p align="justify">
The typical <strong>13 Euromillions payouts levels</strong> and their corresponding odds are displayed in the following table:</p>
<p align="justify">

<table class="zebraTable">
<tbody>
<tr>
<td><strong>Main  numbers</strong></td>  <td><strong>Lucky  stars</strong></td>  <td><strong>Odds of winning</strong></td>  <td><strong>% of fund</strong></td>
</tr>
<tr>  <td>2</td>  <td>0</td>  <td>1 in 23</td> <td>18.0%</td>
</tr>
<tr>  <td>2</td>  <td>1</td>  <td>1 in 46</td><td>17.6%</td>
</tr>
<tr>  <td>1</td>  <td>2</td>  <td>1 in 157</td><td>6.5%</td>
</tr>
<tr>  <td>3</td>  <td>0</td>  <td>1 in 327</td><td>3.7%</td>
</tr>
 <tr>  <td>3</td>  <td>1</td>  <td>1 in 654</td><td>2.2%</td>
</tr>
<tr>  <td>2</td>  <td>2</td>  <td>1 in 822</td> <td>2.3%</td>
 </tr>
 <tr>  <td>3</td>  <td>2</td>  <td>1 in 11,771</td><td>0.5%</td>
 </tr>
 <tr>  <td>4</td>  <td>0</td>  <td>1 in 14,387</td><td>0.7%</td>
 </tr>
 <tr>  <td>4</td>  <td>1</td>  <td>1 in 28,774</td><td>0.7%</td>
 </tr>
 <tr>  <td>4</td>  <td>2</td>  <td>1 in 517,920</td><td>0.8%</td>
 </tr>
 <tr>  <td>5</td>  <td>0</td>  <td>1 in 3,236,995</td><td>1.6%</td>
 </tr>
 <tr>  <td>5</td>  <td>1</td>  <td>1 in 6,473,989</td><td>4.8%</td>
 </tr>
 <tr>  <td>5</td>  <td>2</td>  <td>1 in 116,531,800</td><td>32.0%</td>
 </tr>
 </tbody>
 </table>
<br>
</p>
<br></br>
<a href="https://plus.google.com/109362733485861027705/posts">Anna Dobson</a>
<br></br>'
            ],
            [
                'id' => 2,
                'content' => '<p ALIGN="JUSTIFY">To make sure you fully understand and enjoy playing Euromillions, please take a look at the <a href="{{base_url}}/en">Euromillions.com</a> rules outlined below:</p>
<br></br>
<OL>
  <LI>
    <p ALIGN="JUSTIFY">To be eligible to play Euromillions, all participants must be 18 years or over (16+ in the UK). </p>
  <LI>
    <p ALIGN="JUSTIFY">Anyone from around the world can <a href="http://www.euromillions.com/euromillions/play" rel="nofollow">play Euromillions</a> on the condition that gambling is not prohibited in their country of residence.</p>
  <LI>
    <p ALIGN="JUSTIFY">Euromillions features a jackpot pool cap. It was first introduced on Friday the 6th of March 2009 and corresponded to €185 million (approx. £161 million). Now it  is permanently set at €190 million in accordance with rule changes introduced on the 17th of February 2012. This means that, once the maximum amount is reached, the Euromillions jackpot can not exceed €190 million and can only remain at the aforementioned sum for a further two draws.  In the event that no first prize winner is produced in the second draw, the jackpot will cascade down to the next prize category featuring a winner. Similarly, any additional funds accumulated when the Euromillions jackpot pool cap is reached will roll down to the next prize tier featuring a winner. </p>
  <LI>
    <p ALIGN="JUSTIFY">Entry into the Millionaire Raffle lottery side game is only available upon purchase of a Euromillions ticket in the UK. </p>
  <LI>
    <p ALIGN="JUSTIFY">All Euromillions prizes must be claimed within 12 months of the draw taking place. Failure to do so within the given time will result in your prize being forfeited thus, players are strongly advised to claim their prize immediately following the relevant Euromillions draw.</p>
  <LI>
    <p ALIGN="JUSTIFY">Prizes can only be claimed in the country that issued the winning ticket.</p>
  <LI>
    <p ALIGN="JUSTIFY">Under most circumstances the lottery operator regards the bearer of a Euromillions ticket rightful owner, not the purchaser. This means that should a player unfortunately lose a ticket bought offline, any prize they are entitled to may be claimed by the person who finds the ticket. Thus, it is thoroughly recommend that players sign the back of their Euromillions tickets to avoid this happening, or play Euromillions online where the ticket is directly linked to the purchaser so it can not get lost.  </p>
  <LI>
    <p ALIGN="JUSTIFY">All winnings exceeding €1,000 will only be paid out when a scanned copy of the winner\'s ID or passport has been provided. </p>
  <LI>
    <p ALIGN="JUSTIFY">The state lottery operator will not assume responsibility for any agreements entered into by the ticket bearer with third parties. <BR>
</p>
</OL>
<br></br>
<p ALIGN="JUSTIFY">The above <strong>Euromillions rules</strong> are by no means exhaustive and merely attempt to cover the most important aspects for Euromillions players. For further information please feel free to browse our Euromillions Information page or consult the full Euromillions.com Terms and Conditions</a>.</p>
<p ALIGN="JUSTIFY"><BR>
<br></br>
<a href="https://plus.google.com/109362733485861027705/posts">Anna Dobson</a>
<br></br>',
            ],
            [
                'id' => 3,
                'content' => '<p ALIGN="JUSTIFY"><h3>History of the Lottery</h3><p>
<p ALIGN="JUSTIFY">
The first lottery can be traced as far back as 200 B.C during the Han Dynasty, which is now China. The lottery game, Keno, supposedly still played in modern day casinos, was used to finance governmental projects including none other than the Great Wall of China. Similarly, references to lotteries have also been found in numerous ancient texts from many other civilizations including the Celtic Era and Ancient Greece. However, the first known European lotteries took place during the Roman Empire, mainly for amusement purposes amongst the nobility. The earliest records of the sale of tickets for a lottery date back to the reign of Roman Emperor Cesar Augustus who used the money raised for repairs in the City of Rome.<p>
<p ALIGN="JUSTIFY">
</br>
<p><i>English lottery scroll dated 1566</i></p><p></p>
<br></br>
<p ALIGN="JUSTIFY"><h3>The Modern Lottery</h3></p>
<p ALIGN="JUSTIFY">
Today the lottery is played  across the world, often run by the government of the respective country, with 44 states and territories currently featuring a government-operated lottery. In addition, the internet has revolutionised the way modern lotteries are run with an increasing number of players participating in lottery games through <strong>online lottery agencies</strong>, similar to Euromillions.com. Comprising a safe, reliable and convenient way to play, online lottery sites allow players to participate in any lottery, from anywhere in the world.</p>
<p></p>
<br></br>
<p ALIGN="JUSTIFY"><h3>Beginning of the Euromillions</h3>
<p>
<p ALIGN="JUSTIFY">
The <strong>Euromillions international lottery</strong> was launched in 2004 with the first Euromillions draw held on Friday the 13th of February. It initially featured 3 participating countries. Spain, France and the United Kingdom however, by October that same year a further six countries had joined. Today, nine European countries are officially hosting the Euromillions including <strong>Austria, Belgium, France, Ireland, Luxembourg, Portugal, Spain, Switzerland and the United Kingdom</strong>. Thanks to <strong>online Euromillions lottery agencies</strong>, such as Euromillions.com, lottery hopefuls across the world, as well as residents in the participating countries, can play Euromillions. In addition, the <strong>huge jackpot prizes</strong>, regularly exceeding 100 million euro, have rapidly converted Euromillions into the largest and most popular lottery game in Europe.
</p><p>
<br></br>
<a href="https://plus.google.com/109362733485861027705/posts">Anna Dobson</a>
<br></br>'
            ],
            [
                'id' => 4,
                'content' => '<p ALIGN="JUSTIFY">
The <strong>prize structure of the Euromillions</strong> is rather unique. The Euromillions lottery boasts <strong>13 different prize levels</strong> funded by the money collected from Euromillions ticket purchases. The first 50% of the price for a <strong>Euromillions lottery ticket</strong> goes to the lottery operating company in each country such as the Camelot Group, operators of the UK National Lottery.
<p ALIGN="JUSTIFY">
The remaining 50% is added to the <strong>Common Prize Fund (CPF)</strong> from which all Euromillions prizes are paid out. 91% (approx.) of this huge pot of money is distributed throughout the 13 prize levels (as demonstrated in the table below), whilst 9% (approx.) is contributed towards the <strong>Euromillions Reserve Fund</strong>, or <strong>Booster Fund</strong>. It is used to “boost” the jackpot in the event that the Euromillions first prize needs topping up in order to reach the <strong>guaranteed minimum of €15 million (£12 million)</strong>. </p>
<p ALIGN="JUSTIFY">
A detailed breakdown of the Euromillions odds and <strong>Euromillions prize structure</strong> are provided in the following table: </p>
</br>


<style type="text/css">
table.tableizer-table {
	border: 1px solid #CCC; font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.tableizer-table td {
	padding: 4px;
	margin: 3px;
	border: 1px solid #ccc;
}
.tableizer-table th {
	background-color: #104E8B;
	color: #FFF;
	font-weight: bold;
}
</style>


<table class="zebraTable">
<tr class="tableizer-firstrow"><th><strong>Prize Level</strong><th> <strong>Main No.</strong></th><th><strong>Lucky Stars</strong></th><th><strong>Odds (approx.)</strong></th><th><strong> % of Prize Fund</strong></th><th><strong>Est. Win* (€/£)</strong></th></tr>
<p align="left">
 <tr><td>Tier 13</td><td>2</td><td>0</td><td>1 in 23</td><td>18.0%</td><td>4,00 €/£2.70</td></tr>
 <tr><td>Tier 12</td><td>2</td><td>1</td><td>1 in 46</td><td>17.6%</td><td>8 €/£5.40</td></tr>
 <tr><td>Tier 11</td><td>1</td><td>2</td><td>1 in 157</td><td>6.5%</td><td>10 €/£6.90</td></tr>
 <tr><td>Tier 10</td><td>3</td><td>0</td><td>1 in 327</td><td>3.7%</td><td>12 €/£8.20</td></tr>
 <tr><td>Tier 9</td><td>3</td><td>1</td><td>1 in 654</td><td>2.2%</td><td>14 €/£9.80</td></tr>
 <tr><td>Tier 8</td><td>2</td><td>2</td><td>1 in 822</td><td>2.3%</td><td>19 €/£12.80</td></tr>
 <tr><td>Tier 7</td><td>3</td><td>2</td><td>1 in 11,771</td><td>0.5%</td><td>59 €/£40.10</td></tr>
 <tr><td>Tier 6</td><td>4</td><td>0</td><td>1 in 14,387</td><td>0.7%</td><td>101 €/£68.10</td></tr>
 <tr><td>Tier 5</td><td>4</td><td>1</td><td>1 in 28,774</td><td>0.7%</td><td>201 €/£137.20</td></tr>
 <tr><td>Tier 4</td><td>4</td><td>2</td><td>1 in 517,920</td><td>0.8%</td><td>4,143 €/£2,824,30</td></tr>
 <tr><td>Tier 3</td><td>5</td><td>0</td><td>1 in 3,236,995</td><td>1.6%</td><td>51,792 €/£35,303.90</td></tr>
 <tr><td>Tier 2</td><td>5</td><td>1</td><td>1 in 6,473,989</td><td>4.8%</td><td>310,751 €/£211,823.60</td></tr>
 <tr><td>Jackpot</td><td>5</td><td>2</td><td>1 in 116,531,800</td><td>32.0%</td><td>Jackpot</td></tr>
<tr><td>Booster fund</td><td></td><td></td><td></td><td>8.6%</td><td></td></tr>
 </tbody>
 </table>
</p>
<br></br>
<p ALIGN="JUSTIFY">
* The exact amount of prize money depends on how many Euromillions lottery tickets are purchased and the number of players that have matched the same winning numbers.<p>
<br></br>
<a href="https://plus.google.com/109362733485861027705/posts">Anna Dobson</a>
<br></br>'
            ]
        ];
    }
}
