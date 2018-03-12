<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse"><i class="icon-reorder shaded"></i></a>
            <a class="brand" href="http://www.euromillions.com"><img src="/a/img/euromillions.png" alt="Euromillions"></a>
            <div class="nav-collapse collapse navbar-inverse-collapse">
                <ul class="nav pull-right">
                    <li><a href="/admin">Overview</a></li>
                    <li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Statistics <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/admin/index/business">Business</a></li>
                            <li><a href="/admin/index/system">System</a></li>
                        </ul>
                    </li>
                    <li><a href="/admin/translation/index">Translation</a></li>
                    <li><a href="/admin/index/news">News</a></li>
                    <li class="admin dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Admin Area <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/admin/usersadmin">Admin users</a></li>
                            <li><a href="/admin/users">Users</a></li>
                            <li><a href="/admin/draws">Draws &amp; Jackpot</a></li>
                            <li><a href="/admin/withdraws">Manage Withdrawals</a></li>
                            <li><a href="/admin/millon">Find El Millon</a></li>
                            <li><a href="/admin/reports/playersReports">Reports</a></li>
                            <li><a href="/admin/tracking">Tracking</a></li>
                        </ul>
                    </li>
                    <li class=" dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        User name
                        <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/admin/account">Account Settings</a></li>
                            <li class="divider"></li>
                            <li><a href="/admin/logout">Logout</a></li>
                        </ul>
                    </li>
                    <li><a href="/admin/blog">Blog</a></li>
                </ul>
            </div>
        </div>
    </div>
    {% if needReportsMenu is defined %}
        <div class="navbar-inner">
            <ul class="nav">
                <li><a href="/admin/reports/businessReportsGeneralKPIs">General KPIs</a></li>
                <li><a href="/admin/reports/businessReportsActivity">Activity</a></li>
                <li><a href="/admin/reports/salesDraw">Sales Draw<br />Euromillions</a></li>
                <li><a href="/admin/reports/salesDrawChristmas">Sales Draw<br />Christmas</a></li>
                <li><a href="/admin/reports/playersReports">Players Reports</a></li>
                <!-- li><a href="/admin/reports/salesDraw">Sales Draw</a></li>
                <li><a href="/admin/reports/monthlySales">Monthly Sales</a></li>
                <li><a href="/admin/reports/customerData">Customer data</a></li -->
            </ul>
        </div>
    {% endif %}
    {% if needLanguagesMenu is defined %}
        <div class="navbar-inner">
            <ul class="nav">
                <li><a href="/admin/translation/index">Translation</a></li>
                <li><a href="/admin/translation/categories">Categories</a></li>
                <li><a href="/admin/translation/languages">Languages</a></li>
            </ul>
        </div>
    {% endif %}
</div>