<style>
    p.code {
        padding: 8px;
        background-color: #F3F3F3;
        border: 1px solid #DDD;
    }
    p.code span{
        display: block;
    }

    h2{ position:relative; }
    h2 span.anchor{ position:absolute; top:-80px;}
    a.gotop{
        font-size: 14px;
        font-style: italic;
        padding-left: 15px;
        text-decoration: underline;
        cursor:pointer;
    }
    #settings_form ul {
        list-style-type: disc;
    }
    #content-page ul li {
        padding: 4px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        $('a.gotop').click(function(){
            $('html, body').animate({ scrollTop: 0 }, 'slow');
        });
    });
</script>
<div id="settings_form" style="padding-left: 15px; padding-right: 15px;">
    <h2><?php _e('Plugin information', 'contact_counter') ; ?></h2>
    <p>
        <?php _e('This plugin helps administrators to know how many times an user has contacted another.', 'contact_counter') ; ?>.
    </p>
    <p>
        <?php _e('You can know how many times an user has contacted another knowing the listing or a user, even know the total contacts over the time.', 'contact_counter') ; ?>.
    </p>
    <hr>
    <h2><?php _e('Frequently asked questions', 'contact_counter'); ?></h2>
    <ul>
        <li><a href="#show_listing_contacts"><?php _e('How do I show the number of contacts to a particular listing?', 'contact_counter') ;?></a></li>
        <li><a href="#show_user_contacts"><?php _e('How can I show the number of contacts to a particular user?', 'contact_counter') ;?></a></li>
    </ul>

    <hr>
    <h2><?php _e('Features', 'contact_counter'); ?></h2>
    <p>
        <strong><?php _e('Manage listing', 'contact_counter'); ?></strong>, <?php _e('link to show graph of contacts to a specific listing.', 'contact_counter'); ?></br></br>
        <img src="<?php echo osc_base_url(); ?>oc-content/plugins/contact_counter/img/screenshot_1.png" alt="<?php echo osc_esc_html( __('Manage listings', 'contact_counter') );?>"
    </p>
    <p>
        <strong><?php _e('Contact listing stats', 'contact_counter'); ?></strong>, <?php _e('stadistics of a specific listing, filters by 10 days/weeks/months.', 'contact_counter'); ?></br></br>
        <img src="<?php echo osc_base_url(); ?>oc-content/plugins/contact_counter/img/screenshot_2.png" alt="<?php echo osc_esc_html( __('Manage listings', 'contact_counter') );?>"
    </p>
    <p>
        <strong><?php _e('Contact site stats', 'contact_counter'); ?></strong>, <?php _e('stadistics of contacts in general, filters by 10 days/weeks/months.', 'contact_counter'); ?></br></br>
        <img src="<?php echo osc_base_url(); ?>oc-content/plugins/contact_counter/img/screenshot_3.png" alt="<?php echo osc_esc_html( __('Manage listings', 'contact_counter') );?>"
     </p>
     <hr>



    <h2><span class="anchor" id="show_listing_contacts"></span><?php _e('How do I show the number of contacts to a particular listing?', 'contact_counter') ;?><a class="gotop"><?php _e('Start of page', 'contact_counter'); ?></a></h2>

    <p><?php _e('You can display the number of contacts to a listing wherever you want', 'contact_counter') ; ?>.</p>
    <p><?php _e('By adding this line of code', 'contact_counter') ; ?></p>

    <p class="code">
        <?php echo htmlentities('<?php'); ?> echo cc_contacts_by_listing( osc_item_id() ); ?><br>
    </p>

    <hr/>

    <h2><span class="anchor" id="show_user_contacts"></span><?php _e('How can I show the number of contacts to a particular user?', 'contact_counter') ;?><a class="gotop"><?php _e('Start of page', 'contact_counter'); ?></a></h2>

    <p><?php _e('You can display the number of contacts to a particular user wherever you want', 'contact_counter') ; ?>.</p>
    <p>*(<?php _e('Only will return data if is a registred user, else return 0 contacts', 'contact_counter') ; ?>)</p>
    <p><?php _e('By adding this line of code', 'contact_counter') ; ?></p>

    <p class="code">
        <?php echo htmlentities('<?php'); ?> echo cc_contacts_by_user( osc_user_id() ); ?><br>
    </p>

    <hr/>
</div>