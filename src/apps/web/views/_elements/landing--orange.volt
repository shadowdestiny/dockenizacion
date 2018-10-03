<!DOCTYPE html>
<html>
<head>
    {% include "_elements/meta.volt" %} {# META tags #}
    <link rel="icon" type="image/png" href="/w/img/logo/favicon.png" />

    {# CSS Compress this css in a single file #}
    <link rel="stylesheet" href="/w/css/main.css">
    <link rel="stylesheet" href="/w/css/main_v2.css">
    <link rel="stylesheet" href="/w/css/vendor/jquery.countdownTimer.css">
    {% block template_css %}{% endblock %}      {# Inject unique css #}

    {# FONTS  #}
    {#<link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700'>#}
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,600,700,900" rel="stylesheet">
    <script type="text/javascript" src="//script.crazyegg.com/pages/scripts/0074/6139.js" async="async"></script>

    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function()

        {n.callMethod? n.callMethod.apply(n,arguments):n.queue.push(arguments)}
        ;
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '165298374129776');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=165298374129776&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->
    {% block font %}{% endblock %}
</head>
{% block body %}
<div class="landing landing--orange">
    <header data-role="header" class="landing--header">

        <nav class="landing--top-nav">
            <div class="wrapper">
                <a href="/" class="logo logo-desktop-a ui-link" title="Go to Homepage">
                    <img src="/w/img/logo/v2/logo-desktop.png" alt="Euromillions">
                </a>
                <ul class="ul-top-nav">
                    <li class="li-sign">
                        <a rel="nofollow" class="btn-theme btn-secondary ui-link" href="/sign-up">Sign Up</a>
                    </li>
                    <li class="li-sign">
                        <a rel="nofollow" class="btn-theme btn-primary ui-link" href="/sign-in">Login</a>
                    </li>
                </ul>
            </div>
        </nav>


    </header>
    <div class="content">
        <main id=content>
            <div class="landing--banner-block">

                <div class="landing--banner-block--content">
                    <div class="wrapper">



                        <div class="landing--banner-block--content--left">
                            <div class="landing--banner-block--title">
                                Win a Huge JACKPOT of
                            </div>
                            <div class="landing--banner-block--prize">
                                45m€
                            </div>
                            <div class="landing--banner-block--countdown-block">
                                <div class="lefter"></div>
                                <div class="righter">
                                    <div class="top">
                                        next draw
                                    </div>
                                    <div class="bottom">
                                        3 days : 22hrs : 45 min
                                    </div>
                                </div>
                            </div>

                            <div class="landing--banner-block--star">
                                *Every Friday and Tuesday a new draw to become a millionare
                            </div>
                        </div>

                        <div class="landing--banner-block--content--right">
                            <div class="landing--signin-form sign-up">
                                <h1 class="h1 title">Sign up to create your euroMillions.com account</h1>
                                <form action="/sign-up" onsubmit="fbRegistration()" name="form_notifications" id="sign-up-form"
                                      method="post">

                                    <input type="text" id="name" name="name" class="input" placeholder="Your Name*">
                                    <input type="text" id="surname" name="surname" class="input" placeholder="Your Surname*">
                                    <div class="input--email">
                                        <input type="email" id="email-sign-up" name="email" class="input" placeholder="Email"></div>
                                    <div class="input--password">
                                        <input type="password" id="password-sign-up" name="password" class="input" placeholder="Password"
                                               aria-autocomplete="list"></div>
                                    <div class="input--password">
                                        <input type="password" id="confirm_password" name="confirm_password" class="input"
                                               placeholder="Confirm Password"></div>

                                    <div class="pass-alert">
        <span>
        <svg class="ico v-info">
            <use xlink:href="/w/svg/icon.svg#v-info"></use>
        </svg>
        </span>Password must be at least 6 characters long
                                    </div>

                                    <div class="selectbox">
                                        <select id="country" name="country" class="select">
                                            <option value="">Select your country of residence</option>
                                            <option value="1">Afghanistan</option>
                                            <option value="2">Albania</option>
                                            <option value="3">Algeria</option>
                                            <option value="4">American Samoa</option>
                                            <option value="5">Andorra</option>
                                            <option value="6">Angola</option>
                                            <option value="7">Anguilla</option>
                                            <option value="8">Antarctica</option>
                                            <option value="9">Antigua and Barbuda</option>
                                            <option value="10">Argentina</option>
                                            <option value="11">Armenia</option>
                                            <option value="12">Aruba</option>
                                            <option value="13">Australia</option>
                                            <option value="14">Austria</option>
                                            <option value="15">Azerbaijan</option>
                                            <option value="16">Bahamas</option>
                                            <option value="17">Bahrain</option>
                                            <option value="18">Bangladesh</option>
                                            <option value="19">Barbados</option>
                                            <option value="20">Belarus</option>
                                            <option value="21">Belgium</option>
                                            <option value="22">Belize</option>
                                            <option value="23">Benin</option>
                                            <option value="24">Bermuda</option>
                                            <option value="25">Bhutan</option>
                                            <option value="26">Bolivia</option>
                                            <option value="27">Bosnia and Herzegovina</option>
                                            <option value="28">Botswana</option>
                                            <option value="29">Bouvet Island</option>
                                            <option value="30">Brazil</option>
                                            <option value="31">British Indian Ocean Territory</option>
                                            <option value="32">British Virgin Islands</option>
                                            <option value="33">Brunei</option>
                                            <option value="34">Bulgaria</option>
                                            <option value="35">Burkina Faso</option>
                                            <option value="36">Burundi</option>
                                            <option value="37">Cambodia</option>
                                            <option value="38">Cameroon</option>
                                            <option value="39">Canada</option>
                                            <option value="40">Cape Verde</option>
                                            <option value="41">Cayman Islands</option>
                                            <option value="42">Central African Republic</option>
                                            <option value="43">Chad</option>
                                            <option value="44">Chile</option>
                                            <option value="45">China</option>
                                            <option value="46">Christmas Island</option>
                                            <option value="47">Cocos (Keeling) Islands</option>
                                            <option value="48">Colombia</option>
                                            <option value="49">Comoros</option>
                                            <option value="50">Cook Islands</option>
                                            <option value="51">Costa Rica</option>
                                            <option value="52">Croatia</option>
                                            <option value="53">Cuba</option>
                                            <option value="54">Curaçao</option>
                                            <option value="55">Cyprus</option>
                                            <option value="56">Czech Republic</option>
                                            <option value="57">DR Congo</option>
                                            <option value="58">Denmark</option>
                                            <option value="59">Djibouti</option>
                                            <option value="60">Dominica</option>
                                            <option value="61">Dominican Republic</option>
                                            <option value="62">Ecuador</option>
                                            <option value="63">Egypt</option>
                                            <option value="64">El Salvador</option>
                                            <option value="65">Equatorial Guinea</option>
                                            <option value="66">Eritrea</option>
                                            <option value="67">Estonia</option>
                                            <option value="68">Ethiopia</option>
                                            <option value="69">Falkland Islands</option>
                                            <option value="70">Faroe Islands</option>
                                            <option value="71">Fiji</option>
                                            <option value="72">Finland</option>
                                            <option value="73">France</option>
                                            <option value="74">French Guiana</option>
                                            <option value="75">French Polynesia</option>
                                            <option value="76">French Southern and Antarctic Lands</option>
                                            <option value="77">Gabon</option>
                                            <option value="78">Gambia</option>
                                            <option value="79">Georgia</option>
                                            <option value="80">Germany</option>
                                            <option value="81">Ghana</option>
                                            <option value="82">Gibraltar</option>
                                            <option value="83">Greece</option>
                                            <option value="84">Greenland</option>
                                            <option value="85">Grenada</option>
                                            <option value="86">Guadeloupe</option>
                                            <option value="87">Guam</option>
                                            <option value="88">Guatemala</option>
                                            <option value="89">Guernsey</option>
                                            <option value="90">Guinea</option>
                                            <option value="91">Guinea-Bissau</option>
                                            <option value="92">Guyana</option>
                                            <option value="93">Haiti</option>
                                            <option value="94">Heard Island and McDonald Islands</option>
                                            <option value="95">Honduras</option>
                                            <option value="96">Hong Kong</option>
                                            <option value="97">Hungary</option>
                                            <option value="98">Iceland</option>
                                            <option value="99">India</option>
                                            <option value="100">Indonesia</option>
                                            <option value="101">Iran</option>
                                            <option value="102">Iraq</option>
                                            <option value="103">Ireland</option>
                                            <option value="104">Isle of Man</option>
                                            <option value="105">Israel</option>
                                            <option value="106">Italy</option>
                                            <option value="107">Ivory Coast</option>
                                            <option value="108">Jamaica</option>
                                            <option value="109">Japan</option>
                                            <option value="110">Jersey</option>
                                            <option value="111">Jordan</option>
                                            <option value="112">Kazakhstan</option>
                                            <option value="113">Kenya</option>
                                            <option value="114">Kiribati</option>
                                            <option value="115">Kosovo</option>
                                            <option value="116">Kuwait</option>
                                            <option value="117">Kyrgyzstan</option>
                                            <option value="118">Laos</option>
                                            <option value="119">Latvia</option>
                                            <option value="120">Lebanon</option>
                                            <option value="121">Lesotho</option>
                                            <option value="122">Liberia</option>
                                            <option value="123">Libya</option>
                                            <option value="124">Liechtenstein</option>
                                            <option value="125">Lithuania</option>
                                            <option value="126">Luxembourg</option>
                                            <option value="127">Macau</option>
                                            <option value="128">Macedonia</option>
                                            <option value="129">Madagascar</option>
                                            <option value="130">Malawi</option>
                                            <option value="131">Malaysia</option>
                                            <option value="132">Maldives</option>
                                            <option value="133">Mali</option>
                                            <option value="134">Malta</option>
                                            <option value="135">Marshall Islands</option>
                                            <option value="136">Martinique</option>
                                            <option value="137">Mauritania</option>
                                            <option value="138">Mauritius</option>
                                            <option value="139">Mayotte</option>
                                            <option value="140">Mexico</option>
                                            <option value="141">Micronesia</option>
                                            <option value="142">Moldova</option>
                                            <option value="143">Monaco</option>
                                            <option value="144">Mongolia</option>
                                            <option value="145">Montenegro</option>
                                            <option value="146">Montserrat</option>
                                            <option value="147">Morocco</option>
                                            <option value="148">Mozambique</option>
                                            <option value="149">Myanmar</option>
                                            <option value="150">Namibia</option>
                                            <option value="151">Nauru</option>
                                            <option value="152">Nepal</option>
                                            <option value="153">Netherlands</option>
                                            <option value="154">New Caledonia</option>
                                            <option value="155">New Zealand</option>
                                            <option value="156">Nicaragua</option>
                                            <option value="157">Niger</option>
                                            <option value="158">Nigeria</option>
                                            <option value="159">Niue</option>
                                            <option value="160">Norfolk Island</option>
                                            <option value="161">North Korea</option>
                                            <option value="162">Northern Mariana Islands</option>
                                            <option value="163">Norway</option>
                                            <option value="164">Oman</option>
                                            <option value="165">Pakistan</option>
                                            <option value="166">Palau</option>
                                            <option value="167">Palestine</option>
                                            <option value="168">Panama</option>
                                            <option value="169">Papua New Guinea</option>
                                            <option value="170">Paraguay</option>
                                            <option value="171">Peru</option>
                                            <option value="172">Philippines</option>
                                            <option value="173">Pitcairn Islands</option>
                                            <option value="174">Poland</option>
                                            <option value="175">Portugal</option>
                                            <option value="176">Puerto Rico</option>
                                            <option value="177">Qatar</option>
                                            <option value="178">Republic of the Congo</option>
                                            <option value="179">Romania</option>
                                            <option value="180">Russia</option>
                                            <option value="181">Rwanda</option>
                                            <option value="182">Réunion</option>
                                            <option value="183">Saint Barthélemy</option>
                                            <option value="184">Saint Kitts and Nevis</option>
                                            <option value="185">Saint Lucia</option>
                                            <option value="186">Saint Martin</option>
                                            <option value="187">Saint Pierre and Miquelon</option>
                                            <option value="188">Saint Vincent and the Grenadines</option>
                                            <option value="189">Samoa</option>
                                            <option value="190">San Marino</option>
                                            <option value="191">Saudi Arabia</option>
                                            <option value="192">Senegal</option>
                                            <option value="193">Serbia</option>
                                            <option value="194">Seychelles</option>
                                            <option value="195">Sierra Leone</option>
                                            <option value="196">Singapore</option>
                                            <option value="197">Sint Maarten</option>
                                            <option value="198">Slovakia</option>
                                            <option value="199">Slovenia</option>
                                            <option value="200">Solomon Islands</option>
                                            <option value="201">Somalia</option>
                                            <option value="202">South Africa</option>
                                            <option value="203">South Georgia</option>
                                            <option value="204">South Korea</option>
                                            <option value="205">South Sudan</option>
                                            <option value="206">Spain</option>
                                            <option value="207">Sri Lanka</option>
                                            <option value="208">Sudan</option>
                                            <option value="209">Suriname</option>
                                            <option value="210">Svalbard and Jan Mayen</option>
                                            <option value="211">Swaziland</option>
                                            <option value="212">Sweden</option>
                                            <option value="213">Switzerland</option>
                                            <option value="214">Syria</option>
                                            <option value="215">São Tomé and Príncipe</option>
                                            <option value="216">Taiwan</option>
                                            <option value="217">Tajikistan</option>
                                            <option value="218">Tanzania</option>
                                            <option value="219">Thailand</option>
                                            <option value="220">Timor-Leste</option>
                                            <option value="221">Togo</option>
                                            <option value="222">Tokelau</option>
                                            <option value="223">Tonga</option>
                                            <option value="224">Trinidad and Tobago</option>
                                            <option value="225">Tunisia</option>
                                            <option value="226">Turkey</option>
                                            <option value="227">Turkmenistan</option>
                                            <option value="228">Turks and Caicos Islands</option>
                                            <option value="229">Tuvalu</option>
                                            <option value="230">Uganda</option>
                                            <option value="231">Ukraine</option>
                                            <option value="232">United Arab Emirates</option>
                                            <option value="233">United Kingdom</option>
                                            <option value="234">United States</option>
                                            <option value="235">United States Minor Outlying Islands</option>
                                            <option value="236">United States Virgin Islands</option>
                                            <option value="237">Uruguay</option>
                                            <option value="238">Uzbekistan</option>
                                            <option value="239">Vanuatu</option>
                                            <option value="240">Vatican City</option>
                                            <option value="241">Venezuela</option>
                                            <option value="242">Vietnam</option>
                                            <option value="243">Wallis and Futuna</option>
                                            <option value="244">Western Sahara</option>
                                            <option value="245">Yemen</option>
                                            <option value="246">Zambia</option>
                                            <option value="247">Zimbabwe</option>
                                            <option value="248">Åland Islands</option>
                                        </select></div>

                                    <div class="cl btn-row">
                                        <input id="goSignUp" type="submit" class="hidden2">
                                        <label for="goSignUp" class="submit btn-theme--big">
                                            <span class="resizeme" >Create your account</span>
                                        </label>
                                    </div>

                                    <div class="cl txt--already-have-account">
                                        Already have an account? <a href="/sign-in" class="ui-link">Sign in now</a>
                                    </div>


                                    <div class="cl txt--accept">
                                        <!--<label class="label left" for="accept">-->
                                        <!--<input type="checkbox" id="accept" name="accept" class="checkbox" data-role="none">-->
                                        <!--<span class="checkbox-after">-->
                                        <!--</span>-->
                    <span class="txt">
                        By signing up or logging in you agree to our&nbsp;<a href="/legal/index" rel="nofollow" class="ui-link">Terms
                            &amp; Conditions</a>&nbsp;and our <a href="/legal/privacy" rel="nofollow" class="ui-link">Privacy
                            Policy</a> and you confirm that you are 18+ years old.
                    </span>
                                        <!--</label>-->
                                    </div>

                                </form>


                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="wrapper wrapper--arrows">
                <div class="landing--arrows">
                    <ul>
                        <li class="li-01"><span><i>1.</i>Join Us</span></li>
                        <li class="li-02"><span><i>2.</i> select your <br>numbers</span></li>
                        <li class="li-03"><span><i>3.</i> win big</span></li>
                    </ul>
                </div>
            </div>
            <div class="landing--disclaimer">
                <div class="wrapper">
                    <p>
                        Only play if you are 18+. This service operates under the Gaming License #5536/JAZ authorised and regulated
                        by the Government of Curaçao. This site is operated by Panamedia B.V., Emancipatie Boulevard29,
                        <br>
                        Willemstad, Curaçao and payment processing services are provided by Panamedia International Limited, 30/3
                        Sir Augustus Bartolo Street, XBX 1093, Ta Xbiex Malta (EU). All transactions are charged in Euros. Prices
                        <br>
                        displayed in other currencies are for informative purposes only and are converted according to actual
                        exchange rates. 
                    </p>
                </div>
            </div>
        </main>
    </div>
</div>
{% endblock %}