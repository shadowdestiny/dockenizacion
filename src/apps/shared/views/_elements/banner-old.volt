<div class="large wrapper">
    <div class="banner">
        <div class="box-jackpot">
            <div class="info">{{ language.translate("banner1_head") }}</div>
            <div class="jackpot">
                <svg class="value">
                    <defs>
                        <linearGradient id="e" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="30%" stop-color="#fdf7e0"/>
                            <stop offset="70%" stop-color="#f1d973"/>
                        </linearGradient>
                        <filter id="shadow" height="130%">
                            <feOffset in="SourceAlpha" dx=".5" dy="1" result="myGauss"/>
                            <feGaussianBlur in="myGauss" stdDeviation="2" result="myBlur"/>
                            <feBlend in="SourceGraphic" in2="myBlur"/>
                        </filter>
                    </defs>
                    {% set jackpot_val =  jackpot_value %}
                    <g class="normal">
                        <text filter="url(#shadow)">
                            <tspan class="mycur" y="90"></tspan>
                            <tspan class="mytxt" dx="10px" y="90">{{ jackpot_val }}</tspan>
                        </text>
                        <text fill="url(#e)">
                            <tspan class="mycur" y="90"></tspan>
                            <tspan class="mytxt" dx="10px" y="90">{{ jackpot_val }}</tspan>
                        </text>
                    </g>
                    <g class="small n1" transform="translate(360)">
                        <g text-anchor="middle" x="0">
                            <text filter="url(#shadow)" y="80">
                                <tspan class="mycur"></tspan>
                                <tspan class="mytxt" dx="10px">{{ jackpot_val }}</tspan>
                            </text>
                            <text fill="url(#e)" y="80">
                                <tspan class="mycur"></tspan>
                                <tspan class="mytxt" dx="10px">{{ jackpot_val }}</tspan>
                            </text>
                        </g>
                    </g>
                    <g class="small n2" transform="translate(280)">
                        <g text-anchor="middle" x="0">
                            <text filter="url(#shadow)" y="80">
                                <tspan class="mycur"></tspan>
                                <tspan class="mytxt" dx="10px">{{ jackpot_val }}</tspan>
                            </text>
                            <text fill="url(#e)" y="80">
                                <tspan class="mycur"></tspan>
                                <tspan class="mytxt" dx="10px">{{ jackpot_val }}</tspan>
                            </text>
                        </g>
                    </g>
                    <g class="small n3" transform="translate(240)">
                        <g text-anchor="middle" x="0">
                            <text filter="url(#shadow)" y="70">
                                <tspan class="mycur"></tspan>
                                <tspan class="mytxt">{{ jackpot_val }}</tspan>
                            </text>
                            <text fill="url(#e)" y="70">
                                <tspan class="mycur"></tspan>
                                <tspan class="mytxt">{{ jackpot_val }}</tspan>
                            </text>
                        </g>
                    </g>
                    <g class="small n4" transform="translate(210)">
                        <g text-anchor="middle" x="0">
                            <text filter="url(#shadow)" y="50">
                                <tspan class="mycur"></tspan>
                                <tspan class="mytxt">{{ jackpot_val }}</tspan>
                            </text>
                            <text fill="url(#e)" y="50">
                                <tspan class="mycur"></tspan>
                                <tspan class="mytxt">{{ jackpot_val }}</tspan>
                            </text>
                        </g>
                    </g>
                    <g class="small n5" transform="translate(165)">
                        <g text-anchor="middle" x="0">
                            <text filter="url(#shadow)" y="50">
                                <tspan class="mycur"></tspan>
                                <tspan class="mytxt">{{ jackpot_val }}</tspan>
                            </text>
                            <text fill="url(#e)" y="50">
                                <tspan class="mycur"></tspan>
                                <tspan class="mytxt">{{ jackpot_val }}</tspan>
                            </text>
                        </g>
                    </g>
                    <g class="small n6" transform="translate(135)">
                        <g text-anchor="middle" x="0">
                            <text filter="url(#shadow)" y="45">
                                <tspan class="mycur"></tspan>
                                <tspan class="mytxt">{{ jackpot_val }}</tspan>
                            </text>
                            <text fill="url(#e)" y="45">
                                <tspan class="mycur"></tspan>
                                <tspan class="mytxt">{{ jackpot_val }}</tspan>
                            </text>
                        </g>
                    </g>
                </svg>
            </div>
        </div>
        <div class="btn-box">
            <a href="/{{ language.translate("link_euromillions_play") }}"
               class="btn red huge">{{ language.translate("banner1_btn") }}</a>
            <div class="for-only">{{ language.translate("banner1_subbtn") }} {{ bet_price }}</div>
            <div class="for-only" style="font-size: 12px;">{{ language.translate("nextDraw_lbl") }}:
					<span class="countdown">
						<span class="day unit">
							<span class="val">%-d d</span>
						</span>
						<span class="dots">-</span>
						<span class="hour unit">
							<span class="val">%-H h</span>
						</span>
						<span class="dots">:</span>
						<span class="minute unit">
							<span class="val">%-M m</span>
						</span>
						<span class="dots">:</span>
						<span class="seconds unit">
							<span class="val">%-S s</span>
						</span>
					</span>
            </div>
        </div>
        <div class="txt">{{ language.translate("banner1_subline") }}</div>
        <div class="best-price">
            <picture class="pic">
                <img width="60" height="59" src="/w/img/home/best-price.png"
                     srcset="/w/img/home/best-price@2x.png 1.5x"
                     alt="{{ language.translate('Best Price Guarantee') }}">
            </picture>
        </div>
    </div>
</div>