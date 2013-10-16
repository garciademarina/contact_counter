<?php

    $total_contacts = 0;
    if(Params::getParam('id')!='') {
        $total_contacts = ModelContactCounter::newInstance()->getTotalContactsByItemId( Params::getParam('id') );
    } else {
        $total_contacts = ModelContactCounter::newInstance()->getTotalContacts();
    }

    $total_period = 0;
    $contacts = array();
    if( Params::getParam('type_stat') == 'week' ) {
        if(Params::getParam('id')!='') {
            $total_period  = StatsContactCounter::newInstance()->count_contacts_item(date( 'Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 70, date("Y")) ), Params::getParam('id'),'week');
            $stats_contact = StatsContactCounter::newInstance()->new_contacts_count_item(date( 'Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 70, date("Y")) ), Params::getParam('id'),'week');
        } else {
            $total_period  = StatsContactCounter::newInstance()->count_contacts(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m"), date("d") - 70, date("Y")) ),'week');
            $stats_contact = StatsContactCounter::newInstance()->new_contacts_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m"), date("d") - 70, date("Y")) ),'week');
        }
        for($k = 10; $k >= 0; $k--) {
            $contacts[date( 'W', mktime(0, 0, 0, date("m"), date("d"), date("Y")) ) - $k] = 0;
        }
    } else if( Params::getParam('type_stat') == 'month' ) {
        if(Params::getParam('id')!='') {
            $total_period  = StatsContactCounter::newInstance()->count_contacts_item(date( 'Y-m-d H:i:s', mktime(0, 0, 0, date("m") - 10, date("d"), date("Y")) ), Params::getParam('id'),'month');
            $stats_contact = StatsContactCounter::newInstance()->new_contacts_count_item(date( 'Y-m-d H:i:s', mktime(0, 0, 0, date("m") - 10, date("d"), date("Y")) ), Params::getParam('id'),'month');
        } else {
            $total_period  = StatsContactCounter::newInstance()->count_contacts(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m") - 10, date("d"), date("Y")) ),'month');
            $stats_contact = StatsContactCounter::newInstance()->new_contacts_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m") - 10, date("d"), date("Y")) ),'month');
        }
        for($k = 10; $k >= 0; $k--) {
            $contacts[date( 'F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y")) )] = 0;
        }
    } else {
        if(Params::getParam('id')!='') {
            $total_period  = StatsContactCounter::newInstance()->count_contacts_item(date( 'Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 10, date("Y")) ), Params::getParam('id'),'day');
            $stats_contact = StatsContactCounter::newInstance()->new_contacts_count_item(date( 'Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 10, date("Y")) ), Params::getParam('id'),'day');
        } else {
            $total_period  = StatsContactCounter::newInstance()->count_contacts(date( 'Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 10, date("Y")) ),'day');
            $stats_contact = StatsContactCounter::newInstance()->new_contacts_count(date( 'Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 10, date("Y")) ),'day');
        }
        for($k = 10; $k >= 0; $k--) {
            $contacts[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )] = 0;
        }
    }

    $max          = 0;
    $max_contacts = 0;
    foreach($stats_contact as $contact) {
        $contacts[$contact['d_date']] = $contact['num'];
        if( $contact['num'] > $max ) {
            $max_contacts = $contact['num'];
        }
    }

    View::newInstance()->_exportVariableToView("contacts", $contacts);
    View::newInstance()->_exportVariableToView("max_contacts", $max_contacts);

    $type         = Params::getParam('type_stat');

    switch($type){
        case 'week':
            $type_stat = __('Last 10 weeks');
            break;
        case 'month':
            $type_stat = __('Last 10 months');
            break;
        default:
            $type_stat = __('Last 10 days');
    }

    osc_add_filter('render-wrapper','render_offset');
    function render_offset(){
        return 'row-offset';
    }

    function contact_counter_stats_head() {
        $contacts       = __get("contacts");
        $max_contacts   = __get("max_contacts");

?>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <?php if( count($contacts) > 0 ) { ?>
        <script type="text/javascript">
            // Load the Visualization API and the piechart package.
            google.load('visualization', '1', {'packages':['corechart']});

            // Set a callback to run when the Google Visualization API is loaded.
            google.setOnLoadCallback(drawChart);

            // Callback that creates and populates a data table,
            // instantiates the pie chart, passes in the data and
            // draws it.
            function drawChart() {
                /* ALERTS */
                var data3 = new google.visualization.DataTable();
                data3.addColumn('string', '<?php echo osc_esc_js(__('Date')); ?>');
                data3.addColumn('number', '<?php echo osc_esc_js(__('Contacts')); ?>');


                <?php /* Contacts */
                $k = 0;
                echo "data3.addRows(" . count($contacts) . ");";
                foreach($contacts as $date => $num) {
                    echo "data3.setValue(" . $k . ', 0, "' . $date . '");';
                    echo "data3.setValue(" . $k . ", 1, " . $num . ");";
                    $k++;
                }

                ?>
                var chart = new google.visualization.AreaChart(document.getElementById('placeholder_contacts'));
                chart.draw(data3, {
                    colors:['#058dc7','#e6f4fa'],
                        areaOpacity: 0.1,
                        lineWidth:3,
                        hAxis: {
                        gridlines:{
                            color: '#333',
                            count: 3
                        },
                        viewWindow:'explicit',
                        showTextEvery: 2,
                        slantedText: false,
                        textStyle:{
                            color: '#058dc7',
                            fontSize: 10
                        }
                        },
                        vAxis: {
                            gridlines:{
                                color: '#DDD',
                                count: 4,
                                style: 'dooted'
                            },
                            viewWindow:'explicit',
                            baselineColor:'#bababa'

                        },
                        pointSize: 6,
                        legend: 'none',
                        chartArea:{
                            left:10,
                            top:10,
                            width:"95%",
                            height:"80%"
                        }
                    });


            }
        </script>
<?php

        }
    }


    $item_title = '';
    if(Params::getParam('id')!='' && is_numeric(Params::getParam('id') ) ) {
        $item   = Item::newInstance()->findByPrimaryKey(Params::getParam('id'));
        $item_title  = ' - ' . $item['s_title'];
    }

    osc_add_hook('admin_header', 'contact_counter_stats_head', 10);
    contact_counter_stats_head();
?>
<div class="grid-system" id="stats-page">
    <div class="grid-row grid-50 no-bottom-margin">
        <div class="row-wrapper">
            <h2 class="render-title"><?php _e('Contact Statistics'); ?></h2>
        </div>
    </div>
    <div class="grid-row grid-50 no-bottom-margin">
        <div class="row-wrapper">
            <a id="monthly" class="btn float-right <?php if($type=='month') echo 'btn-green';?>" href="<?php echo osc_route_admin_url('stats-contact-counter', array('type_stat' => 'month', 'id' => Params::getParam('id')));?>"><?php _e('Last 10 months'); ?></a>
            <a id="weekly"  class="btn float-right <?php if($type=='week') echo 'btn-green';?>" href="<?php echo osc_route_admin_url('stats-contact-counter', array('type_stat' => 'week', 'id' => Params::getParam('id')));?>"><?php _e('Last 10 weeks'); ?></a>
            <a id="daily"   class="btn float-right <?php if($type==''||$type=='day') echo 'btn-green';?>" href="<?php echo osc_route_admin_url('stats-contact-counter', array('type_stat' => 'day', 'id' => Params::getParam('id')));?>"><?php _e('Last 10 days'); ?></a>
        </div>
    </div>
    <div class="grid-row grid-50 clear">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title">
                    <h3><?php _e('New contacts', 'contact_counter'); ?><?php echo $item_title; ?></h3>
                </div>
                <div class="widget-box-content">
                    <b class="stats-title"><?php _e('Number of new contacts', 'contact_counter'); ?></b>
                    <div id="placeholder_contacts" class="graph-placeholder" style="height:150px">
                        <?php if( count($contacts) == 0 ) {
                            _e("There're no statistics yet", 'contact_counter');
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title">
                    <h3><?php _e('Contact information', 'contact_counter'); ?><?php echo $item_title; ?></h3>
                </div>
                <div class="widget-box-content" style="text-align: center;">
                    <div style="display: inline-block;position:relative;">
                        <span style="display:inline-block; vertical-align:middle;"><span style="font-size:1.5em;"><?php _e('Period total contacts', 'contact_counter'); ?>  <b><?php echo $total_period; ?></b></span></span>
                    </div>
                    <div class="clear"></div>
                    <div style="display: inline-block;position:relative;">
                        <span style="display:inline-block; vertical-align:middle;"><span style="font-size:1.5em;"><?php _e('All time contacts', 'contact_counter'); ?>  <b><?php echo $total_contacts; ?></b></span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>