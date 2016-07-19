{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/legal.css">{% endblock %}
{% block bodyClass %}terms{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script>{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
           {% set activeSubnav='{"myClass":"terms"}'|json_decode %}
           {% include "legal/_nav.volt" %}
        </div>
        <div class="box-basic content">
            <h1 class="h1 title">{{ language.translate("Terms &amp; Conditions") }}</h1>

            <p>{{ language.translate("Please read these Terms and Conditions carefully before using the website Euromillions.com (“Website”). By using or visiting the Website, players hereby accept and agree to these Terms and Conditions. We highly value the trust of our clients, and encourage you to carefully read the Terms and Conditions and information essential to our Website.") }}</p>

            <h2 class="no-margin h3">{{ language.translate("PARTIES") }}</h2>
            <h2 class="h3">{{ language.translate("Panamedia group") }}</h2>

            <p>{{ language.translate("<strong>Panamedia B.V.</strong>, a private limited liability company organized under and regulated by the government of Curaçao (Kingdom of the Netherlands, European Union), and existing in the Commercial register of Curaçao Number 135786. Panamedia B.V. operates under the Gaming license #5536/JAZ issued by the governor of Curaçao. The Company is the operator of the website Euromillions.com.") }}</p>

            <p>{{ language.translate("<strong>Panamedia International Limited</strong> a private limited liability company in Malta (European Union) with Company Registration Number C53638. Panamedia International Ltd provides payment services to Euromillions.com.") }}</p>

            <h2 class="h3">{{ language.translate("DEFINITIONS") }}</h2>

            <p>{{ language.translate("<strong>Access information</strong> means the unique login credentials generated during the registration process.") }}</p>

            <p>{{ language.translate("<strong>Player account</strong> means a set of dedicated webpages for registered users, used to manage their identification and access data at Euromillions.com, such as accessing their transaction and ticket history in the Euromillions.com website.") }}</p>

            <p>{{ language.translate("<strong>Dispatch deadline</strong> or <strong>Cut off time</strong> means the remaining time until the Euromillions.com website accepts bets for the next draw for a certain game. The Dispatch deadline is based on the local time, local rules of the certain game, and the local opening hours of the local lottery shops.") }}</p> 

            <p>{{ language.translate("<strong>Field</strong> means a single, numbered, clickable area to select the numbers the player wishes to play. A ticket is made of a set of fields.") }}</p>

            <p>{{ language.translate("<strong>Game</strong> means any of the individual or group games available on the Euromillions.com website.") }}</p>

            <p>{{ language.translate("<strong>&euro;</strong> means the Euro currency.") }}</p>

            <p>{{ language.translate("<strong>Group game</strong> or <strong>Syndicates</strong> means a group of users collectively playing a certain number of Tickets and collectively sharing the winnings based on the individual rules of the certain Group Game.") }}</p>

            <p>{{ language.translate("<strong>Euromillions.com website</strong> means the official Euromillions.com website of Euromillions.com, currently accessible at www.Euromillions.com. The official language of the Euromillions.com website is English.") }}</p> 
            
            <p>{{ language.translate("<strong>Official result</strong> means the winning numbers, number of winners, and prizes of each category published by the official lottery operator.") }}</p>

            <p>{{ language.translate("<strong>Ticket</strong> means the online ticket that is filled out on the Euromillions.com website. Tickets are made of a set of Fields.") }}</p>

 {#           <p>{{ language.translate("<strong>Ticket ID</strong> means a unique identification number given to a Ticket that is sent into play. It is available for review under the My Bets menu.") }}</p> #}

{#             <p>{{ language.translate("<strong>Transaction ID</strong> means a unique identifier of a payment. It is available for review under the My Transactions menu.") }}</p> #}

{#             <p>{{ language.translate('<strong>Valid Ticket</strong> means a paid Ticket that has received a valid Ticket ID and has the status of "Paid, Valid".')}}</p> #}

            <p>{{ language.translate("<strong>Winning Numbers</strong> means a set of numbers of the certain game, drawn on a given day and published in a final manner by the official lottery operator.") }}</p>

            <div class="numbered">
                <h2 class="h3">{{ language.translate("1. Introduction") }}</h2>
                <div class="gap">
                    <p>
                        <span class="n">1.1</span> <span class="txt">{{ language.translate("The main purpose of this Euromillions.com website is to enable its registered users to participate without any geographical limitations in any games listed on the Euromillions.com website.")}}</span>
                    </p>
                    <p>
                        <span class="n">1.2</span> <span class="txt"><strong>{{ language.translate("What Euromillions.com Does") }}</strong></span>
                    </p>
                    <div class="gap">
                        <p>
                            <span class="n">1.2.1</span> <span class="txt">{{ language.translate("Customers come to the Euromillions.com website") }}</span>
                        </p>
                        <p>
                            <span class="n">1.2.2</span> <span class="txt">{{ language.translate("Customers select the lottery and the numbers.") }}</span>
                        </p>
                        <p>
                            <span class="n">1.2.3</span> <span class="txt">{{ language.translate("Euromillions.com acts on the placement assignment and keeps track of the status, results and success of the Ticket(s).") }}</span>
                        </p>
                        <p>
                            <span class="n">1.2.4</span> <span class="txt">{{ language.translate("In case of winnings, Euromillions.com assists to claim the prize.") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">1.3</span> 
                        <span class="txt">{{ language.translate("To fulfill its main purpose, the Euromillions.com website provides a variety of additional services, such as providing users a player account, validation of tickets, publishing results of the draws, and collecting and forwarding prizes to the winners in cases where collection is allowed to be executed by the Euromillions.com website.") }}</span>
                    </p>

                    <p><span class="n">1.4</span> <strong><span class="txt">{{ language.translate("What Euromillions.com Does Not") }}</span></strong></p>

                    <div class="gap">
                        <p>
                            <span class="n">1.4.1</span>
                            <span class="txt">{{ language.translate("Euromillions.com does not organize any lottery games. They are existing, offline lotteries independent from the Euromillions.com website or its business entity. The participants understood and acknowledge therefore that the payment after their prizes will come from the offline lottery companies and not directly from the Euromillions.com") }}</span>
                        </p>

                        <p>
                            <span class="n">1.4.2</span>
                            <span class="txt">{{ language.translate("Euromillions.com does NOT take any commission from the winnings as a fee for its service as long as the takeover of the winnings does not cause any costs to Euromillions.com. Euromillions.com only applies a reasonable markup on the individual ticket prices as a fee for its service.") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">1.5</span>
                        <span class="txt">{{ language.translate("Registered users must accept the Terms and Conditions not only at registration, but at each time they send Tickets into game. In every paid game, the participants have the chance to review and agree to the updated Terms and Conditions. With the acceptance of the Terms and Conditions, the participants authorize Euromillions.com to act on their behalf in front of the offline lotteries, and to claim and handle winnings of the participants in all cases where offline lotteries allow agents to takeover the winnings.") }}</span>
                    </p>

                    <p>
                        <span class="n">1.6</span>
                        <span class="txt">{{ language.translate("The Euromillions.com website is entitled to amend these Terms and Conditions at any time, according to its absolute and exclusive discretion, without the obligation of any special notification, except the feature that makes participants agree to the updated Terms and Conditions at each played game.") }}</span>
                    </p>

                    <p>
                        <span class="n">1.7</span>
                        <span class="txt">{{ language.translate("These Terms and Conditions constitute the entire and whole agreement between the Euromillions.com website and its registered users. Any presentation, promise, undertaking or consent, whether verbal or in writing, which does not comply with the Terms and Conditions, will not be valid.") }}</span>
                    </p>

                    <p>
                        <span class="n">1.8</span>
                        <span class="txt">{{ language.translate("In case of disputes, the English Terms and Conditions a used as reference.") }}</span> 
                    </p>

                    <p>
                        <span class="n">1.9</span>
                        <span class="txt">{{ language.translate("In case of any discrepancy between these Terms and Conditions and texts appeared on the applicable screen, these Terms and Conditions shall prevail.") }}</span>
                    </p>
                </div>


                <h2 class="h3">{{ language.translate("2. Participation Requirements") }}</h2>

                <div class="gap">
                    <p>
                        <span class="n">2.1</span>
                        <span class="txt">{{ language.translate("The participation in games or usage of any other services of the Euromillions.com website is permitted for a participant only if:") }}</span>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">2.1.1</span>
                            <span class="txt">{{ language.translate("On the participation date, the participant's age is at least 18 years; and") }}</span>
                        </p>

                        <p>
                            <span class="n">2.1.2</span>
                            <span class="txt">{{ language.translate("The participant is the legal owner of a valid payment vehicle.") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">2.2</span>
                        <span class="txt">{{ language.translate("The participant has the exclusive responsibility to know the rules and conditions of each game, and the local and international legal background of each game.") }}</span>
                    </p>

                    <p>
                        <span class="n">2.3</span>
                        <span class="txt">{{ language.translate("The participant must not violate or breach any local or international law, and the official rules of participation of each game.") }}</span>
                    </p>

                    <p>
                        <span class="n">2.4</span>
                        <span class="txt">{{ language.translate("Upon winning a prize that requires identification, the participant must present the required documentation matching the records in the Euromillions.com website's database.") }}</span>
                    </p>

                    <p>
                        <span class="n">2.5</span>
                        <span class="txt">{{ language.translate("In case of any default, the participant shall not be entitled to obtain any prizes.") }}</span>
                    </p>

                    <p>
                        <span class="n">2.6</span>
                        <span class="txt">{{ language.translate("Before registering or participating in any activity through this Euromillions.com website, it is each participant's responsibility to ensure that he/she complies with any and all laws applicable to him/her.") }}</span> 
                    </p>

                    <p>
                        <span class="n">2.7</span>
                        <span class="txt">{{ language.translate("Due to the regulation limits mentioned in the above laws and rules, a person who is NOT entitled to participate in the games or violates the aforesaid regulations is also NOT entitled to any of the prizes achieved via the Euromillions.com website.") }}</span> 
                    </p>

                    <p>
                        <span class="n">2.8</span>
                        <span class="txt">{{ language.translate("The Euromillions.com website, its agents and anyone on their behalf make no representations or warranties, expressed or implied, as to the lawfulness of any person's participation in any activity through this Euromillions.com website, and to his/her legal right to participate in the lottery draws or any other activity proposed by this Euromillions.com website.") }}</span> 
                    </p>
                </div>

                <h2 class="h3">3. {{ language.translate("Declarations of Participants") }}</h2>

                <p class="gap">
                    <span class="n">3.1</span>
                    <span class="txt">{{ language.translate("With the acceptance of the present Terms and Conditions, the participant declares that according to the laws of his/her own country where he/she is a citizen, the participant is entitled to participate in the game he/she plays on this website. Participant also declares that during the use of this Website, he/she acts on behalf his/her own and there is no other person who is represented by his/her.") }}</span>
                </p>

                <h2 class="h3">4. {{ language.translate("Registration") }}</h2>

                <div class="gap">
                    <p>
                        <span class="n">4.1</span>
                        <span class="txt">{{ language.translate("Registration is free and required to use the services of the Euromillions.com website. Participants must ensure that their data is true, and must inform Euromillions.com immediately of any change in the data supplied by them during registration, including changes in their credit card or bank account data, by modifying their own accounts on this website.") }}</span>
                    </p>

{#                    <div class="gap">
                        <p>
                            <span class="n">4.1.1</span>
                            <span class="txt">{{ language.translate("The first step is to complete the registration form with valid data. Participants must ensure that their data are true, and must inform Euromillions.com immediately of any change in the data supplied by them during registration, including changes in their credit card or bank account data, by modifying their own accounts on this website.") }}</span>
                        </p>

                        <p>
                            <span class="n">4.1.2</span>
                            <span class="txt">{{ language.translate("The second step is confirmation, which is performed by clicking a confirmation link sent to the registrant via e-mail to the e-mail address provided in the first step.") }}</span>
                        </p>
                    </div> #}

                    <p>
                        <span class="n">4.2</span>
                        <span class="txt">{{ language.translate("The participant has the entire responsibility to reserve the confidentiality of the identification and Euromillions.com website access details, and not to make them available to anyone else.") }}</span>
                    </p>

                    <p>
                        <span class="n">4.3</span>
                        <span class="txt">{{ language.translate("The currently available technologies can only allow Euromillions.com website to check the validity and correctness of the Euromillions.com website access credentials, but are not suitable to validate the legal ownership and use of such. Therefore, the full responsibility for an unauthorized use of the registrant's identification and access details lies solely on the registrant.") }}</span>
                    </p>

                    <p>
                        <span class="n">4.4</span>
                        <span class="txt">{{ language.translate("Euromillions.com shall not be liable for any failure or to deliver any prizes if any information provided by the relevant participant is incomplete, incorrect, or out of date. Euromillions.com shall not be obliged in any way to get connection to any participant in order to complete or correct any information which may seem to be incomplete, incorrect or out of date.") }}</span>
                    </p>

                    <h2 class="h3">5. {{ language.translate("Player Account") }}</h2>

                    <p>
                        <span class="n">5.1</span>
                        <span class="txt">{{ language.translate("Each registrant will have access to his/her own player account. Access to the player account is only possible with valid credentials. Without valid credentials, the player account is not accessible.") }}</span>
                    </p>

                    <p>
                        <span class="n">5.2</span>
                        <span class="txt">{{ language.translate("Registrants have the chance to modify their identification data, view their transactions, bets, notification settings, and perform deposits and withdrawals through their player account.") }}</span>
                    </p>

                    <p>
                        <span class="n">5.3</span>
                        <span class="txt">{{ language.translate("Player accounts shall be established for the exclusive personal use of the registrant. Registrant undertakes not to create an account for anyone else or use anyone else’s account. Player accounts shall not be subject to any kind of transfer or assignment. Registrant shall be solely responsible for any and all activity that occurs in his/her account.") }}</span>
                    </p>

                    <p>
                        <span class="n">5.4</span>
                        <span class="txt">{{ language.translate("Registrant hereby acknowledges and declares that he/she is the exclusive owner of all money transferred onto his/her account and all transferred money has clear legal title.") }}</span>
                    </p>

                    <p>
                        <span class="n">5.5</span>
                        <span class="txt">{{ language.translate("Euromillions.com may at any time, without reason, refuse money lodgment or transfer to any registrants’ account at its sole discretion.") }}</span> 
                    </p>
                </div>

                <h2 class="h3">6. {{ language.translate("Confidentiality") }}</h2>

                <div class="gap">
                    <p>
                        <span class="n">6.1</span>
                        <span class="txt">{{ language.translate("Customer data are strictly confidential and will be used solely by Euromillions.com, subject to exceptions contained in these Terms and Conditions or under applicable law.") }}</span>
                    </p>

                    <p>
                        <span class="n">6.2</span>
                        <span class="txt">{{ language.translate("The Company adheres to the Data Protection Act, other relevant regulations, legal notices and/or similar at the Place of the Contract taking account of the Data Protection Directive (EC Directive 95/46/EC) and the Electronic Communications Privacy Directive (EC Directive/2002/58/EC).") }}</span>
                    </p>

                    <p>
                        <span class="n">6.3</span>
                        <span class="txt">{{ language.translate("Euromillions.com ensures their account holders that, at all times that:") }}</span>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">6.3.1</span>
                            <span class="txt">{{ language.translate("all personal data is processed in accordance with the rights of the account holder concerned;")}}</span>
                        </p>

                        <p>
                            <span class="n">6.3.2</span>
                            <span class="txt">{{ language.translate("all personal data is processed fairly and lawfully;") }}</span> 
                        </p>

                        <p>
                            <span class="n">6.3.3</span>
                            <span class="txt">{{ language.translate("all personal data is always processed in accordance with good practice;") }}</span>
                        </p>

                        <p>
                            <span class="n">6.3.4</span>
                            <span class="txt">{{ language.translate("personal data is only collected for specific, explicitly stated and legitimate purposes;") }}</span>
                        </p>

                        <p>
                            <span class="n">6.3.5</span>
                            <span class="txt">{{ language.translate("personal data is not processed for any purpose that is incompatible with that for which the information is collected;") }}</span>
                        </p>

                        <p>
                            <span class="n">6.3.6</span>
                            <span class="txt">{{ language.translate("personal data that is processed is adequate and relevant in relation to the purposes of the processing;") }}</span>
                        </p>

                        <p>
                            <span class="n">6.3.7</span>
                            <span class="txt">{{ language.translate("no more personal data is processed than is necessary with regards to the purposes of the processing;") }}</span>
                        </p>

                        <p>
                            <span class="n">6.3.8</span>
                            <span class="txt">{{ language.translate("the personal data that is processed is correct and, if necessary, up to date;") }}</span>
                        </p>

                        <p>
                            <span class="n">6.3.9</span>
                            <span class="txt">{{ language.translate("no personal data is kept for a period longer than is necessary, with regards to the purposes for which they are processed; and") }}</span>
                        </p>

                        <p>
                            <span class="n">6.3.10</span>
                            <span class="txt">{{ language.translate("all reasonable measures are taken to complete, correct, block or erase data to the extent that such data is incomplete or incorrect, having regard to the purposes for which they are processed.") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">6.4</span>
                        <span class="txt">{{ language.translate("Euromillions.com discloses personal data when ordered to do so by the Governing Authorities and/or under provision in the Governing Law. Furthermore, the Company reserves the right to disclose personal data to relevant recipients when the Company has reasonable grounds to suspect irregularities that involve a bet-at-home betting account.") }}</span>
                    </p>

                    <p>
                        <span class="n">6.5</span>
                        <span class="txt">{{ language.translate("Euromillions.com reserves the right to process personal data for CRM (Customer Relationship Management) purposes.") }}</span>
                    </p>
                </div>

                <h2 class="h3">7. Paid Participation</h2>

                <div class="gap">
                    <p>
                        <span class="n">7.1</span>
                        <strong><span class="txt">{{ language.translate("Purpose of the Game") }}</span></strong>
                    </p>


                    <div class="gap">
                        <p>
                            <span class="n">7.1.1</span>
                            <span class="txt">{{ language.translate("The Game's purpose is to guess and select the winning numbers that will be drawn and published in a final manner by the official lottery operator. Suitable matches, their prize categories and prizes are also set according to the publications by the official lottery operator.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.1.2</span>
                            <span class="txt">{{ language.translate("Participants can win various monetary prizes, equal to those granted in the official lottery draws. As mentioned in the Participation requirements, participation in a Game is subject to, and will be in accordance with, the official and valid rules of each and every official lottery game.") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">7.2</span>
                        <strong><span class="txt">{{ language.translate("Participation Requirements") }}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.2.1</span>
                            <span class="txt">{{ language.translate("Registrants who comply with the participation requirements, mentioned above and with valid access information, have the possibility to send the filled out lottery tickets into play after payment.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.2.2</span>
                            <span class="txt">{{ language.translate("The Euromillions.com website, from time to time, offers a Welcome bonus package in which it lets its first-time players access a free gift or offer. Contents, amounts and conditions in parts or the Welcome bonus in its whole can be a subject to immediate change or suspension at the sole discretion of the operator of the Euromillions.com website.") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">7.3</span>
                        <strong><span class="txt">{{ language.translate("Participation Flow") }}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.3.1</span>
                            <span class="txt">{{ language.translate("Participation is done by purchasing a Ticket, that will be filled in according to the official rules of the given lottery game.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.3.2</span>
                            <span class="txt">{{ language.translate("From time to time, mostly but not limited to, prior to the official lottery draws as per their rules, the Euromillions.com website might block the Tickets for a short period of time. Along with and during the time of such blocking, the Euromillions.com website stops providing its services for the currently upcoming draw or offers the possibility to send the Tickets to play for the very next upcoming draw.") }}</span>
                        </p>

{#                      <p>
                            <span class="n">7.3.3</span>
                            <span class="txt">{{ language.translate('When a participant sends the Ticket(s) into play, the transaction gets a unique Transaction ID, with a "Not Paid" Status and gets forwarded for payment. From this moment on, the transaction is available for review under the My Transactions menu.')}}</span>
                        </p> #}

                       <p>
                            <span class="n">7.3.3</span>
                            <span class="txt">{{ language.translate('In case of an unsuccessful payment, the Transaction status under the My Transactions menu will be classed as "Payment Failed", the Tickets included in that transaction will not be processed further on, and will not participate in any draws. These Tickets will not be shown on the Tickets page, as they never got paid.') }}</span>
                        </p>

                        <p>
                            <span class="n">7.3.4</span>
                            <span class="txt">{{ language.translate("In case of a successful payment:") }}</span>
                        </p>

{#                        <div class="gap">
                            <p>
                                <span class="n">a.</span>
                                <span class="txt">{{ language.translate('The Status of the transaction gets updated to "Paid" under the My Transactions menu;') }}</span>
                            </p>

                            <p>
                                <span class="n">b.</span>
                                <span class="txt">{{ language.translate('The Tickets, each played on separate dates, will be available for review under the My Bets menu. Their status will be "Placement Pending" while they are forwarded for placement.')}}</span>
                            </p>

                            <p>
                                <span class="n">c.</span>
                                <span class="txt">{{ language.translate('A "Transaction Notification"')}} {{ language.translate("confirmation email will be sent out to the participants' email, separately for each game, containing a unique Ticket ID for each Ticket, and all the played numbers and their settings.") }}</span>
                            </p>
                        </div> #}

                        <div class="gap">
{#                             <p>
                                <span class="n">7.3.4.1</span>
                                <span class="txt">{{ language.translate("The participant has the responsibility to preserve the Ticket ID, and to present it to the Euromillions.com website upon its request. The Euromillions.com website reserves the right to reject all claims, including but not limited to winnings, if the participant is not able to present at least the valid Ticket ID and draw date. A Ticket ID and draw date are considered valid if the data completely match with the Euromillions.com website's records along with the Identification and Payment data of the user. In case of disputes, the Euromillions.com website's records are considered the official and valid records.") }}</span>
                            </p> #}

                            <p>
                                <span class="n">7.3.4.1</span>
                                <span class="txt">{{ language.translate("The payment for the Tickets is performed either by debiting the participants’ player account balance or by charging the participants credit card by the Euromillions.com website's payment processor. Payments and purchases are final and cannot be cancelled or refunded as they will be forwarded along in the placement flow.") }}</span>
                            </p>

                            <p>
                                <span class="n">7.3.4.2</span>
                                <span class="txt">{{ language.translate('Only Tickets with the Transaction status of "Paid" will be processed further on.') }}</span>
                            </p>

                            <p>
                                <span class="n">7.3.4.3</span>
                                <span class="txt">{{ language.translate("A Ticket that for any reason, including but not limited to technical malfunctioning, technical failure, human error, risk assessment filtering, potential fraud filtering, external forces or anything else, does not get successfully placed, will be automatically reimbursed to the Participant's player account, and will not entitle the participant who filled out the Ticket any rights, including but not limited to appeal against the cause or its consequences.") }}</span>
                            </p>

                            <p>
                                <span class="n">7.3.4.4</span>
                                <span class="txt">{{ language.translate('The Euromillions.com website and its system is entitled to retroactively cancel and recall a Ticket, if it is found by Euromillions.com that the placement for the Ticket has not been fully settled for any reason, including but not limited to technical malfunctioning, technical failure, human error, risk assessment filtering, potential fraud filtering, external forces, or any other reason. In such an event, the Ticket will be considered as having been invalid from the start and will be considered recalled.') }}</span>
                            </p>

                            <p>
                                <span class="n">7.3.4.5</span>
                                <span class="txt">{{ language.translate('Immediately after the winning numbers, the number of winners, and the winning sums in each of the official lottery draws will be published by the official operator of the lottery draw (the "Official Results"), the Euromillions.com website will conduct a comprehensive examination of all Valid Tickets. The Euromillions.com website will examine whether, amongst the Valid Tickets, there are selections that entitle a participant or any participants to prizes.')}}</span>
                            </p>
                        </div>
                    </div>

                    <p>
                        <span class="n">7.4</span>
                        <strong><span class="txt">{{ language.translate("Group Games") }}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.4.1</span>
                            <span class="txt">{{ language.translate("The participation rules of Group Games, which are available from time to time on Euromillions.com, are the same as described for individual games in these Terms and Conditions.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.4.2</span>
                            <span class="txt">{{ language.translate("The individual rules of the certain Group Game are listed on the Game's webpage on Euromillions.com, as is.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.4.3</span>
                            <span class="txt">{{ language.translate("Euromillions.com reserves the right to make partial, immediate or future, complete or partial changes or suspensions to Group Games at its sole discretion without the obligation of any prior notice to the Euromillions.com website's registrants.") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">7.5</span>
                        <strong><span class="txt">{{ language.translate("Results, Winnings and Payouts") }}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.5.1</span>
                            <span class="txt">{{ language.translate("Results of a game will be published on the Euromillions.com website. In parallel and immediately with the publication of the Results on the Euromillions.com website, an optional notification e-mail will be sent to the electronic address that was given by the Participant.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.5.2</span>
                            <span class="txt">{{ language.translate("In the event that a validated bet includes a winning selection or selections that entitle a prize up to the amount the official lottery operator does not require personal identification, the Euromillions.com website will transfer the winning sum to the Participant's player account shortly after the publication of the results on the Euromillions.com website. The Participant can withdraw the winning sum at any time as long as the amount of winnings or the accumulated amount of winnings is equal or higher than €25. An optional notification e-mail is sent to the Participant, containing the details of the deposited amount.") }}</span>
                        </p>

                        <p class="gap">
                            <span class="n">7.5.2.1</span>
                            <span class="txt">{{ language.translate("Winnings will be credited to the player account in Euro, and will bear no interest or linkage differentials from the winning date until the actual crediting of the winning sums or thereafter. Non-Euro prizes will be converted to Euro according to the rules detailed in 9.4.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.5.3</span>
                            <span class="txt">{{ language.translate("When a validated bet includes a winning selection or selections that entitle a prize above the amount for which the official lottery operator requires personal identification of the winner, e.g.: the Jackpot, or the second prize, Euromillions.com will have the right to choose the manner of the prize collection through:") }}</span>
                        </p>

                        <div class="gap">
                            <p>
                                <span class="n">7.5.3.1</span>
                                <span class="txt">{{ language.translate("either instructing the winner to take part of the identification proceeding, and collect the amount from the official lottery operator directly;") }}</span>
                            </p>

                            <p>
                                <span class="n">7.5.3.2</span>
                                <span class="txt">{{ language.translate("or Euromillions.com shall collect the prize on the winner’s behalf at the winner’s costs, and forward to the winner the amount of the prize left after deduction of costs.") }}</span>
                            </p>
                        </div>

                        <p>
                            <span class="n">7.5.4</span>
                            <span class="txt">{{ language.translate("In cases where the winner collects the winnings in person, the Euromillions.com website’s representative will hand over the winning ticket to the customer.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.5.5</span>
                            <span class="txt">{{ language.translate("In cases where the Euromillions.com website collects the winning sum on the winner’s behalf, Euromillions.com will transfer the winning sum primarily to the Participant's player account; and in case of first or second prize category, the amounts left after costs deduction, to his/her Bank account via a Bank Transfer. The Euromillions.com website will transfer the winning sum according to the Participant's instructions, deducting any transfer costs, within 30 days from the receipt of the winning sum from the official draw operator.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.5.6</span>
                            <span class="txt">{{ language.translate("The participant alone will be responsible for the payment of the taxes that will apply, if at all, on the winning sums. However, the Euromillions.com website will be entitled, but not obliged, to deduct from the winning sums any tax that will apply to the winning sums concerning a withholding tax obligation.")}}</span>
                        </p>

                        <p>
                            <span class="n">7.5.7</span>
                            <span class="txt">{{ language.translate("The Participant is solely responsible for complying with the provisions of any and all law concerning him/her according to his/her citizenship.") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">7.6</span>
                        <strong><span class="txt">{{ language.translate("Refunds, Returns and Cancellations")}}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.6.1</span>
                            <span class="txt">{{ language.translate("The payment for the purchase of a ticket is performed either by debiting the participant’s player balance, or by charging the participants credit card via the Euromillions.com website's payment processor. After paying for the ticket(s), the Euromillions.com website forwards the payment to our ticket provider with whom all transactions are final, therefore payments and purchases are final and cannot be cancelled or refunded.</br></br> The EU Consumer Rights legislation requiring a 14 day cooling off period for online purchases does not apply to services for specific dates or event tickets. Therefore, the services supplied by the company do not fall  under the remit of this legislation.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.6.2</span>
                            <span class="txt">{{ language.translate("As per 11.3, the Euromillions.com website reserves the right to cancel, terminate, modify or suspend any of the tickets if for any reason, the ticket purchase cannot be conducted as planned for any reason, including infection by computer virus, bugs, tampering or unauthorized intervention, fraud, technical failures or any other causes beyond the control of the Euromillions.com website.") }}</span>
                        </p>
                    </div>

                    <p>
                        <span class="n">7.7</span>
                        <strong><span class="txt">{{ language.translate("Withdrawal") }}</span></strong>
                    </p>

                    <div class="gap">
                        <p>
                            <span class="n">7.7.1</span>
                            <span class="txt">{{ language.translate("As a fraud prevention measure, only winnings from purchased tickets can be withdrawn. The withdrawable balance is visible in your player account. Withdrawals can only be made if the player account and payee account have the same name.") }}</span> 
                        </p>

                        <p>
                            <span class="n">7.7.2</span>
                            <span class="txt">{{ language.translate("Every withdrawal is manually verified in compliance with anti-money laundering policies.  Bank transaction fees apply on payouts; these can vary depending on the transferred amount and destination country of the bank.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.7.3</span>
                            <span class="txt">{{ language.translate("The minimum withdrawal amount is €25. For withdrawals over €250, the user may be requested to present his/her ID with a photograph, name and address. The Company may also request a copy of his/her ID that is certified by two (2) witnesses, the names, addresses and ID numbers of the certifier witnesses must be clearly indicated. The copy must be certified as a “true copy of the original seen and the photograph being of a reasonable likeness to the bearer of the ID document”. In addition to the mentioned ID, a certified copy and data of either of the following documents may also be requested:") }}</span>
                        </p>

                        <div class="gap">
                            <p>
                                <span class="n">a.</span>
                                <span class="txt">{{ language.translate("Document which indicates the full name of the user together with his residential address; or") }}</span>
                            </p>

                            <p>
                                <span class="n">b.</span>
                                <span class="txt">{{ language.translate("A scanned of a utility bill which indicates the full name of the user together with his residential address on it.") }}</span>
                            </p>

                            <p>
                                <span class="n">c.</span>
                                <span class="txt">{{ language.translate("All data on the scanned documents must match the user’s data in our system.") }}</span>
                            </p>
                        </div>
                        <p>
                            <span class="n">7.7.4</span>
                            <span class="txt">{{ language.translate("History and data of the withdrawal transactions are accessible by logged in users under the My Transactions.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.7.5</span>
                            <span class="txt">{{ language.translate("In case of withdrawal, all legal titles of the transactions shall be indicated on the financial statement sent by Euromillions.com to the participant’s registered e-mail address.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.7.6</span>
                            <span class="txt">{{ language.translate("Euromillions.com keeps record of all transactions been made on the participant’s player account, wherein Euromillions.com defines the legal title of the transactions. Following type of legal titles are used:") }}</span> 
                        </p>

                        <div class="gap">
                            <p>
                                <span class="n">a.</span>
                                <span class="txt">{{ language.translate("cash deposit: lodged money to the player account;") }}</span>
                            </p>

                            <p>
                                <span class="n">b.</span>
                                <span class="txt">{{ language.translate("payment: purchasing lottery ticket;") }}</span>
                            </p>

                            <p>
                                <span class="n">c.</span>
                                <span class="txt">{{ language.translate("winning payout: prize payment; and") }}</span>
                            </p>

                            <p>
                                <span class="n">d.</span>
                                <span class="txt">{{ language.translate("withdrawal: withdrawing money from the player account.") }}</span>
                            </p>
                        </div>

                        <p>
                            <span class="n">7.7.7</span>
                            <span class="txt">{{ language.translate("The participant shall be solely and fully liable for the reporting and payment of any and all taxes and charges arising from or imposed on the amounts deposited, paid, transferred or withdrawn by his/her player account.") }}</span>
                        </p>

                        <p>
                            <span class="n">7.7.8</span>
                            <span class="txt">{{ language.translate("Participant is aware of and acknowledges that the official lottery operators might retain a portion of the prize and forward it to the competent taxing authority on his/her behalf.") }}</span>
                        </p>
                    </div>
                </div>

                <p>
                    <span class="n">8.</span>
                    <strong><span class="txt">{{ language.translate("Prices") }}</span></strong>
                </p>

                <div class="gap">
                    <p>
                        <span class="n">8.1</span>
                        <span class="txt">{{ language.translate("The price of participation in a Game may be more than the participation price that is collected by the official operator for a single draw.") }}</span>
                    </p>

                    <p>
                        <span class="n">8.2</span>
                        <span class="txt">{{ language.translate("Participation price of each Game via the Euromillions.com website may change from one draw to another.") }}</span>
                    </p>

                    <p>
                        <span class="n">8.3</span>
                        <span class="txt">{{ language.translate("In every event, the participation price via the Euromillions.com website is the price appearing on the Ticket and in the Basket. The final debiting sum for the participation is the sum appearing in the Payment confirmation form and in the Participant's Account.") }}</span>
                    </p>
                </div>

                <p>
                    <span class="n">9.</span>
                    <strong><span class="txt">{{ language.translate("Currency") }}</span></strong>
                </p>

                <div class="gap">
                    <p>
                        <span class="n">9.1</span>
                        <span class="txt">{{ language.translate("The operating currency of the Euromillions.com website is EURO. Debit transactions of the participation price and payment of the prize moneys to the winners will be made in EURO.") }}</span>
                    </p>

                    <p>
                        <span class="n">9.2</span>
                        <span class="txt">{{ language.translate("In any event in which an official lottery draw is conducted in a currency different than EURO, then for the publication on the Euromillions.com website of the prize sums in that draw, an approximate conversion of the prizes will be made from the currency in which they are supposed to be paid in to EURO.") }}</span>
                    </p>

                    <p>
                        <span class="n">9.3</span>
                        <span class="txt">{{ language.translate("All the exchanges from various currencies to EURO will be performed using current conversion rates that will be periodically published by banking institutions.") }}</span>
                    </p>

                    <p>
                        <span class="n">9.4</span>
                        <span class="txt">{{ language.translate("Winnings in currencies other than EURO will be converted to EURO based on the latest periodically published exchange rate, and then credited to the player’s account.") }}</span>
                    </p>

                    <p>
                        <span class="n">9.5</span>
                        <span class="txt">{{ language.translate("The Euromillions.com website will be entitled to deduct reasonable fees arising from currency exchange costs.") }}</span>
                    </p>
                </div>

                <p>
                    <span class="n">10.</span>
                    <strong><span class="txt">{{ language.translate("Information") }}</span></strong>
                </p>

                <div class="gap">
                    <p>
                        <span class="n">10.1</span>
                        <span class="txt">{{ language.translate("The Euromillions.com website provides various official information about the worldwide leading lottery draws, including winning numbers, official rules, and official results.") }}</span>
                    </p>

                    <p>
                        <span class="n">10.2</span>
                        <span class="txt">{{ language.translate("The publication of the Information on the Euromillions.com website is given only as a service to the participants, and nothing more. The information feed into the Euromillions.com website's operating system may include errors and mistakes made in bona fide.") }}</span>
                    </p>
                </div>

                <p>
                    <span class="n">11.</span>
                    <strong><span class="txt">{{ language.translate("Reservations Concerning Responsibilities") }}</span></strong>
                </p>
                
                <div class="gap">
                    <p>
                        <span class="n">11.1</span>
                        <span class="txt">{{ language.translate("Euromillions.com is not responsible for the damages arisen from the breach of contract by any of the official lottery game organizers or operators.") }}</span>
                    </p>

                    <p>
                        <span class="n">11.2</span>
                        <span class="txt">{{ language.translate("The Euromillions.com website does and will, to the best of its ability, prevent any malfunctioning in the Euromillions.com website's operations and activity. However, in any event of a technical failure in the Euromillions.com website's operating or computer system, human error or external force, that as a result thereof, for example, an offline ticket, has not been purchased, or confusion regarding the transfer of the selection that was marked on a validated bet, the Euromillions.com website will be entitled to cancel any validated bet, concerning which the malfunctioning has occurred. In such an event, the responsibility of the Euromillions.com website and/or its operator and/or owner and/or anyone else who has acted on their behalf or for them will be limited only to the participation fees sum that was paid to the Euromillions.com website by the participant for the validated bet.") }}</span>
                    </p>

                    <p>
                        <span class="n">11.3</span>
                        <span class="txt">{{ language.translate("In addition to the above, the Euromillions.com website and/or anyone else who has acted on their behalf are not responsible for any error, omission, interruption, deletion, default, defect, delay in operation or transmission, communications line failure, theft, destruction, unauthorized access to, or alteration of data or information. The Euromillions.com website is not responsible for any problems or technical malfunction of any telephone network or lines, computers, on-line systems, servers or providers, computer equipment, software failure of email on account of technical problems or traffic congestion on the Internet or at the Euromillions.com website. The Euromillions.com website reserves the right to cancel, terminate, modify or suspend the Games if for any reason, the Games cannot be conducted as planned, including infection by computer virus, bugs, tampering or unauthorized intervention, fraud, technical failures or any other causes beyond the control of the Euromillions.com website.") }}</span>
                    </p>

                    <p>
                        <span class="n">11.4</span>
                        <span class="txt">{{ language.translate("In no event shall the Euromillions.com website and/or the Euromillions.com website and/or anyone else who has acted on their behalf (including the accountants, auditors, etc.) be liable for any direct, indirect, incidental, special or consequential damages or damages for loss of profits, revenue, data or use incurred by any participant or third party, whether in an action for contract or tort, arising from the access to, or use of, the Euromillions.com website.") }}</span>

                    <p>
                        <span class="n">11.5</span>
                        <span class="txt">{{ language.translate("The responsibility of the Euromillions.com website for the payment of the prize due to participation via the Euromillions.com website in any lottery draw will only arise and be limited only to any sum that the Euromillions.com website will receive from the operator of the official draw.") }}</span> 
                    </p>

                    <p>
                        <span class="n">11.6</span>
                        <span class="txt">{{ language.translate("In any event, the Euromillions.com website and/or anyone on their behalf will not have to pay the participant any sum due to a win of a first prize if the Euromillions.com website will fail, in spite of its reasonable efforts, to collect this prize from the operator of the official draw, for any reason whatsoever.")}}</span>
                    </p>

                    <p>
                        <span class="n">11.7</span>
                        <span class="txt">{{ language.translate('Euromillions.com and/or its agents make no representations about the suitability, reliability, availability, timeliness and accuracy of the information, software, products and services contained on the Euromillions.com website for any purpose. All information, software, products and services are provided "as is" without warranty of any kind. Euromillions.com hereby disclaims all warranties with respect to this information, whether expressed or implied.') }}</span>
                    </p>

                    <p>
                        <span class="n">11.8</span>
                        <span class="txt">{{ language.translate("In any event, no liability will apply to the Euromillions.com website and/or anyone operating in their name or on their behalf due to a damage that was caused due to reliance, of any type, on the Information, as defined above, or on any other publication appearing on the Euromillions.com website, and the participants or any one surfing on the Euromillions.com website are invited to verify the information published on the Euromillions.com website via the terminals and the official publications of the official lottery games operators.") }}</span>
                    </p>

                    <p>
                        <span class="n">11.9</span>
                        <span class="txt">{{ language.translate("The Euromillions.com website reserves the right to immediately ban further access of any participants and their payment vehicles with a certain number without prior notice who has broken these Terms and Conditions and especially the rules set in the referred laws and regulations in the Participation requirements presumably or whose presence and/or participation cause legal risk or action against the Euromillions.com website. In case of such banning, the Euromillions.com website will pay out any positive balances within a thirty (30) day period to the participant.") }}</span>
                    </p>
                </div>

                <h2 class="h3">12. {{ language.translate("Intellectual Property") }}</h2>

                <div class="gap">
                    <p>
                        <span class="n">12.1</span>
                        <span class="txt">{{ language.translate("The Euromillions.com website is not related, connected or affiliated to any of the official game organizers. All logos and trademarks used on the Euromillions.com website are the properties of such game organizers and the Euromillions.com website only uses them for informational purposes only.") }}</span>
                    </p>

                    <p>
                        <span class="n">12.2</span>
                        <span class="txt">{{ language.translate("All the rights, including the intellectual property rights (i.e., patents, copyright, trademarks, service marks, logos, trade names, know-how or any other intellectual property or right) concerning this Euromillions.com website or relating to it are owned by the Euromillions.com website and shall be the exclusive property of the Euromillions.com website. No one shall use any of the Rights without the expressed written approval of Euromillions.com.") }}</span>
                    </p>

                    <p>
                        <span class="n">12.3</span>
                        <span class="txt">{{ language.translate("No one should copy or duplicate, in any form whatsoever and in any media whatsoever, any part of this Terms and Conditions, or of the rules the Euromillions.com website, and the Games proposed therein.") }}</span>
                    </p>

                    <p>
                        <span class="n">12.4</span>
                        <span class="txt">{{ language.translate("Each violation of the Euromillions.com website's rights will be severely handled, and Euromillions.com will be strict in extracting its rights against anyone who will violate them.") }}</span>
                    </p>
                </div>

                <p>
                    <h2 class="h3">13. {{ language.translate("Applicable Law") }}</h2>
                </p> 

                <div class="gap">
                    <p>
                        <span class="n">13.1</span>
                        <span class="txt">{{ language.translate("In any event of a clarification, complaint, dispute, reservation, claim or action resulting from participation in any activity on the Euromillions.com website, which is not resolved between the Euromillions.com website and the registrant, the dispute will be transferred to the exclusive decision of the legal jurisdiction where the Euromillions.com company is registered.")}}</span>
                    </p>

                    <p>
                        <span class="n">13.2</span>
                        <span class="txt">{{ language.translate("The legal relationship between the registrant and Euromillions.com shall be governed by law of the legal jurisdiction where the Euromillions.com company is registered, with exclusion of the reference norm of the international civil rights. All disputes in connection with or arising out of any game shall only be settled by the competent court having local and subject matter jurisdiction in the place where the Euromillions.com company is registered.") }}</span>
                    </p>
                </div>

            </div>
        </div>
    </div>
</main>
{% endblock %}
