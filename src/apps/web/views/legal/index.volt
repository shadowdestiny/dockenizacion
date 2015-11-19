{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/legal.css">{% endblock %}
{% block bodyClass %}privacy{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
           {% set activeSubnav='{"myClass":"terms"}'|json_decode %}
           {% include "legal/_nav.volt" %}
        </div>
        <div class="box-basic content">
            <h1 class="h1 title">{{ language.translate("Terms &amp; Condition") }}</h1>

            <p>Please read these Terms and Conditions carefully before using the website Euromillions.com (“Website”). By using or visiting the Website, players hereby accept and agree to these Terms and Conditions. We highly value the trust of our clients, and encourage you to carefully read the Terms and Conditions and information essential to our Website. </p>

            <h2 class="no-margin h3">{{ language.translate("PARTIES") }}</h2>
            <h2 class="h3">{{ language.translate("Panamedia group") }}</h2>

            <p><strong>Panamedia B.V.</strong>, a private limited liability company organized under and regulated by the government of Curaçao (Kingdom of the Netherlands, European Union), and existing in the Commercial register of Curaçao Number 135786. Panamedia B.V. operates under the Gaming license #5536/JAZ issued by the governor of Curaçao. The Company is the operator of the website Euromillions.com.</p>

            <p><strong>Panamedia Interactive SLU</strong>, a private limited liability company in Spain (European Union) with tax identification number B66106295. Panamedia Interactive SLU provides software engineering, promotion and customer services for Euromillions.com.</p>

            <p><strong>Panamedia International Limited</strong> a private limited liability company in Malta (European Union) with Company Registration Number C53638. Panamedia International Ltd provides payment services for Euromillions.com.</p>

            <h2 class="h3">{{ language.translate("DEFINITIONS") }}</h2>

            <p><strong>Access information</strong> means the unique login credentials generated during the registration process.</p>

            <p><strong>Player account</strong> means a set of dedicated webpages for registered users, used to manage their identification and access data at Euromillions.com, such as accessing their transaction and ticket history in the Euromillions.com website.</p>

            <p><strong>Dispatch deadline</strong> or <strong>Cut off time</strong> means the remaining time until the Euromillions.com website accepts bets for the next draw for a certain game. The Dispatch deadline is based on the local time, local rules of the certain game, and the local opening hours of the local lottery shops.</p> 

            <p><strong>Field</strong> means a single, numbered, clickable area to select the numbers the player wishes to play. A ticket is made of a set of fields.</p>

            <p><strong>Game</strong> means any of the individual or group games available on the Euromillions.com website.</p>

            <p><strong>&euro;</strong> means the Euro currency.</p>

            <p><strong>Group game</strong> or <strong>Syndicates</strong> means a group of users collectively playing a certain number of Tickets and collectively sharing the winnings based on the individual rules of the certain Group Game.</p>

            <p><strong>Euromillions.com website</strong> means the official Euromillions.com website of Euromillions.com, currently accessible at www.Euromillions.com. The official language of the Euromillions.com website is English.</p> 
            
            <p><strong>Official result</strong> means the winning numbers, number of winners, and prizes of each category published by the official lottery operator.</p>

            <p><strong>Ticket</strong> means the online ticket that is filled out on the Euromillions.com website. Tickets are made of a set of Fields.</p>

            <p><strong>Ticket ID</strong> means a unique identification number given to a Ticket that is sent into play. It is available for review under the My Bets menu.</p>

            <p><strong>Transaction ID</strong> means a unique identifier of a payment. It is available for review under the My Transactions menu.</p>

            <p><strong>Valid Ticket</strong> means a paid Ticket that has received a valid Ticket ID and has the status of "Paid, Valid".</p> 

            <p><strong>Winning Numbers</strong> means a set of numbers of the certain game, drawn on a given day and published in a final manner by the official lottery operator.</p>

            <ol class="ol-list">
                <li>
                    <h2 class="h3">Introduction</h2>
                    <ol class="ol">
                        <li><p>The main purpose of this Euromillions.com website is to enable its registered users to participate without any geographical limitations in any games listed on the Euromillions.com website.</p></li>
                    </ol>
                </li>
            </ol>
                

        </div>
    </div>
</main>
{% endblock %}