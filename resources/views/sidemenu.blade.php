<ul class="sidebar-menu">
    <li class="header">MAIN NAVIGATION</li>
    <li class="active">
        <a href="{{url('dashboard')}}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>            
        </a>        
    </li>
    <li {!! Request::is('allcards') || Request::is('add_card') || Request::is('allblacklistedcards') ? ' class="treeview active"' : ' class="treeview"' !!}>
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Manage Cards</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li {!! Request::is('allcards') ? ' class="active"' : null !!}><a href="{{url('allcards')}}"><i class="fa fa-circle"></i> All Cards</a></li>
            @if(Entrust::can('cards.create'))
            <li {!! Request::is('add_card') ? ' class="active"' : null !!}><a href="{{url('add_card')}}"><i class="fa fa-circle"></i> New Card</a></li>
            <li {!! Request::is('allblacklistedcards') ? ' class="active"' : null !!}><a href="{{url('allblacklistedcards')}}"><i class="fa fa-circle"></i> Blacklisted Cards</a></li>
            @endif
        </ul>
    </li>
    @if(Entrust::can('range.operations'))
    <li {!! Request::is('create_card_range') || Request::is('entitle_card_range') || Request::is('unentitle_card_range') || Request::is('fingerprint_range') || Request::is('blacklist_range') || Request::is('unblacklist_range') || Request::is('reassign_card_range') || Request::is('message_card_range') ? ' class="treeview active"' : ' class="treeview"' !!}>
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Manage Card Range</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            @if(Entrust::can('range.card.create'))
            <li {!! Request::is('create_card_range') ? ' class="active"' : null !!}><a href="{{url('create_card_range')}}"><i class="fa fa-circle"></i> Create Card Range</a></li>
            @endif
            @if(Entrust::can('range.entitle.create'))
            <li {!! Request::is('entitle_card_range') ? ' class="active"' : null !!}><a href="{{url('entitle_card_range')}}"><i class="fa fa-circle"></i> Entitle Card Range</a></li>
            @endif
            @if(Entrust::can('range.unentitle.create'))
            <li {!! Request::is('unentitle_card_range') ? ' class="active"' : null !!}><a href="{{url('unentitle_card_range')}}"><i class="fa fa-circle"></i> Unentitle Card Range</a></li>
            @endif
            @if(Entrust::can('range.fingerprint.create'))
            <li {!! Request::is('fingerprint_range') ? ' class="active"' : null !!}><a href="{{url('fingerprint_range')}}"><i class="fa fa-circle"></i> Fingerprint Range</a></li>
            @endif 
            @if(Entrust::can('range.blacklist.create'))
            <li {!! Request::is('blacklist_range') ? ' class="active"' : null !!}><a href="{{url('blacklist_range')}}"><i class="fa fa-circle"></i> Blacklist Range</a></li>
            @endif
            @if(Entrust::can('range.unblacklist.create'))
            <li {!! Request::is('unblacklist_range') ? ' class="active"' : null !!}><a href="{{url('unblacklist_range')}}"><i class="fa fa-circle"></i> Unblacklist Range</a></li>
            @endif
            @if(Entrust::can('range.reassign.card.access'))
            <li {!! Request::is('range.reassign.card.access') ? ' class="active"' : null !!}><a href="{{url('reassign_card_range')}}"><i class="fa fa-circle"></i> Reassign Card Range</a></li>
            @endif
            @if(Entrust::can('range.message.send'))
            <li {!! Request::is('message_card_range') ? ' class="active"' : null !!}><a href="{{url('message_card_range')}}"><i class="fa fa-circle"></i> Message Card Range</a></li>
            @endif
        </ul>
    </li>
    @endif
    <li {!! Request::is('allsettopboxes') || Request::is('add_set_top_box') ? ' class="treeview active"' : ' class="treeview"' !!}>
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Manage Set Top Box</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            @if(Entrust::can('settopbox.read'))
            <li {!! Request::is('allsettopboxes') ? ' class="active"' : null !!}><a href="{{url('allsettopboxes')}}"><i class="fa fa-circle"></i> All Set Top Boxes</a></li>
            @endif
            @if(Entrust::can('settopbox.create'))
            <li {!! Request::is('add_set_top_box') ? ' class="active"' : null !!}><a href="{{url('add_set_top_box')}}"><i class="fa fa-circle"></i> New Set Top Box</a></li>
            @endif 
        </ul>
    </li>
    <li {!! Request::is('allcustomers') || Request::is('create_customer') ? ' class="treeview active"' : ' class="treeview"' !!}>
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Manage Customers</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            @if(Entrust::can('subscribers.read'))
            <li {!! Request::is('allcustomers') ? ' class="active"' : null !!}><a href="{{url('allcustomers')}}"><i class="fa fa-circle"></i> All Customers</a></li>
            @endif
            @if(Entrust::can('subscribers.create'))
            <li {!! Request::is('create_customer') ? ' class="active"' : null !!}><a href="{{url('create_customer')}}"><i class="fa fa-circle"></i> New Customer</a></li>
            @endif            
        </ul>
    </li>
    @if(Entrust::can('product.operations'))
    <li {!! Request::is('allchannels') || Request::is('add_channel') || Request::is('allproducts') || Request::is('add_product') ? ' class="treeview active"' : ' class="treeview"' !!}>
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Manage Products</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li {!! Request::is('allchannels') ? ' class="active"' : null !!}><a href="{{url('allchannels')}}"><i class="fa fa-circle"></i> All Channels</a></li>
            <li {!! Request::is('add_channel') ? ' class="active"' : null !!}><a href="{{url('add_channel')}}"><i class="fa fa-circle"></i> New Channel</a></li>
            <li {!! Request::is('allproducts') ? ' class="active"' : null !!}><a href="{{url('allproducts')}}"><i class="fa fa-circle"></i> All Products</a></li>
            <li {!! Request::is('add_product') ? ' class="active"' : null !!}><a href="{{url('add_product')}}"><i class="fa fa-circle"></i> New Product</a></li>
        </ul>
    </li>
    @endif
    @if(Entrust::can('distributors.operations'))
    <li {!! Request::is('alldistributors') || Request::is('create_distributor') || Request::is('revoke_distributor') || Request::is('allsubdistributors') || Request::is('create_sub_distributor') ? ' class="treeview active"' : ' class="treeview"' !!}>
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Manage Distributors</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            @if(Entrust::can('distributors.read'))
            <li {!! Request::is('alldistributors') ? ' class="active"' : null !!}><a href="{{url('alldistributors')}}"><i class="fa fa-circle"></i> All Distributors</a></li>
            @endif
            @if(Entrust::can('distributors.create'))
            <li {!! Request::is('create_distributor') ? ' class="active"' : null !!}><a href="{{url('create_distributor')}}"><i class="fa fa-circle"></i> New Distributor</a></li>
            @endif
            @if(Entrust::can('distributors.revoke'))
            <li {!! Request::is('revoke_distributor') ? ' class="active"' : null !!}><a href="{{url('revoke_distributor')}}"><i class="fa fa-circle"></i> Revoke Distributor</a></li>
            @endif
            <li {!! Request::is('allsubdistributors') ? ' class="active"' : null !!}><a href="{{url('allsubdistributors')}}"><i class="fa fa-circle"></i> All Sub Distributors</a></li>
            <li {!! Request::is('create_sub_distributor') ? ' class="active"' : null !!}><a href="{{url('create_sub_distributor')}}"><i class="fa fa-circle"></i> New Sub Distributor</a></li>            
        </ul>
    </li>
    @endif
    <li {!! Request::is('send*') || Request::is('allosdmessages') ? ' class="treeview active"' : ' class="treeview"' !!}>
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Manage Messages</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li {!! Request::is('sendosd') ? ' class="active"' : null !!}><a href="{{url('sendosd')}}"><i class="fa fa-circle"></i> Send OSD</a></li>
            <li {!! Request::is('sendemail') ? ' class="active"' : null !!}><a href="{{url('sendemail')}}"><i class="fa fa-circle"></i> Send Email</a></li>
            <li {!! Request::is('allosdmessages') ? ' class="active"' : null !!}><a href="{{url('allosdmessages')}}"><i class="fa fa-circle"></i> All OSD Messages</a></li>
        </ul>
    </li>
    <li {!! Request::is('*allcardentitlementhistory') || Request::is('*allfingerprinthistory') || Request::is('*allblacklisthistory') ? ' class="treeview active"' : ' class="treeview"' !!}>
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>History</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li {!! Request::is('allcardentitlementhistory') ? ' class="active"' : null !!}><a href="{{url('allcardentitlementhistory')}}"><i class="fa fa-circle"></i> Card Entitlement History</a></li>
            <li {!! Request::is('allfingerprinthistory') ? ' class="active"' : null !!}><a href="{{url('allfingerprinthistory')}}"><i class="fa fa-circle"></i> Fingerprint History</a></li>
            <li {!! Request::is('allblacklisthistory') ? ' class="active"' : null !!}><a href="{{url('allblacklisthistory')}}"><i class="fa fa-circle"></i> Blacklist History</a></li>            
        </ul>
    </li>
    @if(Entrust::hasRole('admin'))
        <li {!! Request::is('allusers') || Request::is('create_users') ? ' class="treeview active"' : ' class="treeview"' !!}>
            <a href="#">
                <i class="fa fa-files-o"></i>
                <span>Users</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                @if(Entrust::can('users.read'))
                    <li {!! Request::is('allusers') ? ' class="active"' : null !!}><a href="{{url('allusers')}}"><i class="fa fa-circle"></i> All User</a></li>
                @endif
                @if(Entrust::can('users.create'))
                    <li {!! Request::is('create_users') ? ' class="active"' : null !!}><a href="{{url('create_users')}}"><i class="fa fa-circle"></i> New User</a></li> 
                @endif           
            </ul>
        </li>
    @endif
    <li {!! Request::is('*profile') ? ' class="treeview active"' : ' class="treeview"' !!}>
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>My Profile</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{url('edit_users_own_profile')}}"><i class="fa fa-circle"></i> Edit My Profile</a></li>
        </ul>
    </li>
    <li {!! Request::is('*report') ? ' class="treeview active"' : ' class="treeview"' !!}>
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>General Reports</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{url('#')}}"><i class="fa fa-circle"></i> View Report</a></li>
        </ul>
    </li>
    @if(Entrust::hasRole('admin'))
        <li {!! Request::is('roles') || Request::is('permissions') ? ' class="treeview active"' : ' class="treeview"' !!}>
            <a href="#">
                <i class="fa fa-gears"></i>
                <span>Settings</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li {!! Request::is('roles') || Request::is('permissions') ? ' class="treeview active"' : ' class="treeview"' !!}>
                    <a href="#"><i class="fa fa-circle"></i> Permissions
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        @if(Entrust::can('roles.access'))
                            <li {!! Request::is('roles') ? ' class="active"' : null !!}><a href="{{url('roles')}}"><i class="fa fa-circle"></i> Roles</a></li>
                        @endif 
                        @if(Entrust::can('permissions.access'))
                            <li {!! Request::is('permissions') ? ' class="active"' : null !!}><a href="{{url('permissions')}}"><i class="fa fa-circle"></i> Permission</a></li>
                        @endif 
                    </ul>
                </li>
            </ul>
        </li>
    @endif
</ul>