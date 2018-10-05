<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Overriden theme boost core renderer.
 *
 * @package    theme_moove
 * @copyright  2017 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_moove\output;

use html_writer;
use custom_menu_item;
use custom_menu;
use action_menu_filler;
use action_menu_link_secondary;
use navigation_node;
use action_link;
use stdClass;
use moodle_url;
use action_menu;
use pix_icon;
use theme_config;
use core_text;
use help_icon;
use context_system;

defined('MOODLE_INTERNAL') || die;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_moove
 * @copyright  2017 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \theme_boost\output\core_renderer {

    /**
     * Renders the custom menu
     *
     * @param custom_menu $menu
     * @return mixed
     */
    protected function render_custom_menu(custom_menu $menu) {
        global $CFG;

        if (!$menu->has_children()) {
            return '';
        }

        $content = '';
        foreach ($menu->get_children() as $item) {
            $context = $item->export_for_template($this);
            $content .= $this->render_from_template('core/custom_menu_item', $context);
        }

        return $content;
    }

    /**
     * Renders the lang menu
     *
     * @return mixed
     */
    public function render_lang_menu() {
        $langs = get_string_manager()->get_list_of_translations();
        $haslangmenu = $this->lang_menu() != '';
        $menu = new custom_menu;

        if ($haslangmenu) {
            $strlang = get_string('language');
            $currentlang = current_language();
            if (isset($langs[$currentlang])) {
                $currentlang = $langs[$currentlang];
            } else {
                $currentlang = $strlang;
            }
            $this->language = $menu->add($currentlang, new moodle_url('#'), $strlang, 10000);
            foreach ($langs as $langtype => $langname) {
                $this->language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }

            foreach ($menu->get_children() as $item) {
                $context = $item->export_for_template($this);
            }

            if (isset($context)) {
                return $this->render_from_template('theme_moove/lang_menu', $context);
            }
        }
    }

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function mydashboard_admin_header() {
        global $PAGE;

        $html = html_writer::start_div('row');
        $html .= html_writer::start_div('col-xs-12 p-a-1');

        $pageheadingbutton = $this->page_heading_button();
        if (empty($PAGE->layout_options['nonavbar'])) {
            $html .= html_writer::start_div('clearfix w-100 pull-xs-left', array('id' => 'page-navbar'));
            $html .= html_writer::tag('div', $this->navbar(), array('class' => 'breadcrumb-nav'));
            $html .= html_writer::div($pageheadingbutton, 'breadcrumb-button');
            $html .= html_writer::end_div();
        } else if ($pageheadingbutton) {
            $html .= html_writer::div($pageheadingbutton, 'breadcrumb-button nonavbar pull-xs-right m-r-1');
        }

        $html .= html_writer::end_div(); // End .row.
        $html .= html_writer::end_div(); // End .col-xs-12.

        return $html;
    }

    /**
     * Renders the login form.
     *
     * @param \core_auth\output\login $form The renderable.
     * @return string
     */
    public function render_login(\core_auth\output\login $form) {
        global $SITE;

        $context = $form->export_for_template($this);

        // Override because rendering is not supported in template yet.
        $context->cookieshelpiconformatted = $this->help_icon('cookiesenabled');
        $context->errorformatted = $this->error_text($context->error);

        $context->logourl = $this->get_logo();
        $context->sitename = format_string($SITE->fullname, true, array('context' => \context_course::instance(SITEID)));

        return $this->render_from_template('core/login', $context);
    }

    /**
     * Gets the logo to be rendered.
     *
     * The priority of get log is: 1st try to get the theme logo, 2st try to get the theme logo
     * If no logo was found return false
     *
     * @return mixed
     */
    public function get_logo() {
        if ($this->should_display_theme_logo()) {
            return $this->get_theme_logo_url();
        }

        $url = $this->get_logo_url();
        if ($url) {
            return $url->out(false);
        }

        return false;
    }

    /**
     * Outputs the pix url base
     *
     * @return string an URL.
     */
    public function get_pix_image_url_base() {
        global $CFG;

        return $CFG->wwwroot . "/theme/moove/pix";
    }

    /**
     * Whether we should display the main theme logo in the navbar.
     *
     * @return bool
     */
    public function should_display_theme_logo() {
        $logo = $this->get_theme_logo_url();

        return !empty($logo);
    }

    /**
     * Get the main logo URL.
     *
     * @return string
     */
    public function get_theme_logo_url() {
        $theme = theme_config::load('moove');

        return $theme->setting_file_url('logo', 'logo');
    }

    /**
     * Return the site identity providers
     *
     * @return mixed
     */
    public function get_identity_providers() {
        global $CFG;

        $authsequence = get_enabled_auth_plugins(true);

        require_once($CFG->libdir . '/authlib.php');

        $identityproviders = \auth_plugin_base::get_identity_providers($authsequence);

        return $identityproviders;
    }

    /**
     * Verify whether the site has identity providers
     *
     * @return mixed
     */
    public function has_identity_providers() {
        global $CFG;

        $authsequence = get_enabled_auth_plugins(true);

        require_once($CFG->libdir . '/authlib.php');

        $identityproviders = \auth_plugin_base::get_identity_providers($authsequence);

        return !empty($identityproviders);
    }

    /**
     * Construct a user menu, returning HTML that can be echoed out by a
     * layout file.
     *
     * @param stdClass $user A user object, usually $USER.
     * @param bool $withlinks true if a dropdown should be built.
     * @return string HTML fragment.
     */
    public function user_menu($user = null, $withlinks = null) {
        global $USER, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');

        if (is_null($user)) {
            $user = $USER;
        }

        // Note: this behaviour is intended to match that of core_renderer::login_info,
        // but should not be considered to be good practice; layout options are
        // intended to be theme-specific. Please don't copy this snippet anywhere else.
        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }

        // Add a class for when $withlinks is false.
        $usermenuclasses = 'usermenu';
        if (!$withlinks) {
            $usermenuclasses .= ' withoutlinks';
        }

        $returnstr = "";

        // If during initial install, return the empty return string.
        if (during_initial_install()) {
            return $returnstr;
        }

        $loginpage = $this->is_login_page();
        $loginurl = get_login_url();
        // If not logged in, show the typical not-logged-in string.
        if (!isloggedin()) {
            $returnstr = get_string('loggedinnot', 'moodle');
            if (!$loginpage) {
                $returnstr .= " (<a href=\"$loginurl\">" . get_string('login') . '</a>)';
            }

            return html_writer::tag(
                'li',
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                array('class' => $usermenuclasses)
            );
        }

        // If logged in as a guest user, show a string to that effect.
        if (isguestuser()) {
            $returnstr = get_string('loggedinasguest');
            if (!$loginpage && $withlinks) {
                $returnstr .= " (<a href=\"$loginurl\">".get_string('login').'</a>)';
            }

            return html_writer::tag(
                'li',
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                array('class' => $usermenuclasses)
            );
        }

        // Get some navigation opts.
        $opts = user_get_user_navigation_info($user, $this->page);

        $avatarclasses = "avatars";
        $avatarcontents = html_writer::span($opts->metadata['useravatar'], 'avatar current');
        $usertextcontents = '';

        // Other user.
        if (!empty($opts->metadata['asotheruser'])) {
            $avatarcontents .= html_writer::span(
                $opts->metadata['realuseravatar'],
                'avatar realuser'
            );
            $usertextcontents = $opts->metadata['realuserfullname'];
            $usertextcontents .= html_writer::tag(
                'span',
                get_string(
                    'loggedinas',
                    'moodle',
                    html_writer::span(
                        $opts->metadata['userfullname'],
                        'value'
                    )
                ),
                array('class' => 'meta viewingas')
            );
        }

        // Role.
        if (!empty($opts->metadata['asotherrole'])) {
            $role = core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['rolename'],
                'meta role role-' . $role
            );
        }

        // User login failures.
        if (!empty($opts->metadata['userloginfail'])) {
            $usertextcontents .= html_writer::span(
                $opts->metadata['userloginfail'],
                'meta loginfailures'
            );
        }

        // MNet.
        if (!empty($opts->metadata['asmnetuser'])) {
            $mnet = strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['mnetidprovidername'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['mnetidprovidername'],
                'meta mnet mnet-' . $mnet
            );
        }

        $returnstr .= html_writer::span(
            html_writer::span($usertextcontents, 'usertext') .
            html_writer::span($avatarcontents, $avatarclasses),
            'userbutton'
        );

        // Create a divider (well, a filler).
        $divider = new action_menu_filler();
        $divider->primary = false;

        $am = new action_menu();
        $am->set_menu_trigger(
            $returnstr
        );
        $am->set_alignment(action_menu::TR, action_menu::BR);
        $am->set_nowrap_on_items();
        if ($withlinks) {
            $navitemcount = count($opts->navitems);
            $idx = 0;

            // Adds username to the first item of usermanu.
            $userinfo = new stdClass();
            $userinfo->itemtype = 'text';
            $userinfo->title = $user->firstname . ' ' . $user->lastname;
            $userinfo->url = new moodle_url('/user/profile.php', array('id' => $user->id));
            $userinfo->pix = 'i/user';

            array_unshift($opts->navitems, $userinfo);

            foreach ($opts->navitems as $key => $value) {

                switch ($value->itemtype) {
                    case 'divider':
                        // If the nav item is a divider, add one and skip link processing.
                        $am->add($divider);
                        break;

                    case 'invalid':
                        // Silently skip invalid entries (should we post a notification?).
                        break;

                    case 'text':
                        $al = new action_menu_link_secondary(
                            $value->url,
                            $pix = new pix_icon($value->pix, $value->title, null, array('class' => 'iconsmall')),
                            $value->title,
                            array('class' => 'text-username')
                        );

                        $am->add($al);
                        break;

                    case 'link':
                        // Process this as a link item.
                        $pix = null;
                        if (isset($value->pix) && !empty($value->pix)) {
                            $pix = new pix_icon($value->pix, $value->title, null, array('class' => 'iconsmall'));
                        } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                            $value->title = html_writer::img(
                                $value->imgsrc,
                                $value->title,
                                array('class' => 'iconsmall')
                            ) . $value->title;
                        }

                        $al = new action_menu_link_secondary(
                            $value->url,
                            $pix,
                            $value->title,
                            array('class' => 'icon')
                        );
                        if (!empty($value->titleidentifier)) {
                            $al->attributes['data-title'] = $value->titleidentifier;
                        }
                        $am->add($al);
                        break;
                }

                $idx++;

                // Add dividers after the first item and before the last item.
                if ($idx == 1 || $idx == $navitemcount) {
                    $am->add($divider);
                }
            }
        }

        return html_writer::tag(
            'li',
            $this->render($am),
            array('class' => $usermenuclasses)
        );
    }

    /**
     * Secure login info.
     *
     * @return string
     */
    public function secure_login_info() {
        return $this->login_info(false);
    }

    /**
     * Implementation of user image rendering.
     *
     * @param help_icon $helpicon A help icon instance
     * @return string HTML fragment
     */
    public function render_help_icon(help_icon $helpicon) {
        $context = $helpicon->export_for_template($this);
        // Solving the issue - "Your progress" help tooltip in course home page displays in outside the screen display.
        // Check issue https://github.com/willianmano/moodle-theme_moove/issues/5.
        if ($helpicon->identifier === 'completionicons' && $helpicon->component === 'completion') {
            $context->ltr = right_to_left();
        }

        return $this->render_from_template('core/help_icon', $context);
    }

    /**
     * Returns a search box.
     *
     * @param  string $identifier The search box wrapper div id, defaults to an autogenerated one.
     * @return string HTML with the search form hidden by default.
     */
    public function search_box($identifier = false) {
        global $CFG;

        // Accessing $CFG directly as using \core_search::is_global_search_enabled would
        // result in an extra included file for each site, even the ones where global search
        // is disabled.
        if (empty($CFG->enableglobalsearch) || !has_capability('moodle/search:query', context_system::instance())) {
            return '';
        }

        if ($identifier == false) {
            $identifier = uniqid();
        } else {
            // Needs to be cleaned, we use it for the input id.
            $identifier = clean_param($identifier, PARAM_ALPHANUMEXT);
        }

        // JS to animate the form.
        $this->page->requires->js_call_amd('core/search-input', 'init', array($identifier));

        $iconattrs = array(
                        'class' => 'icon-magnifier',
                        'title' => get_string('search', 'search'),
                        'aria-label' => get_string('search', 'search'),
                        'aria-hidden' => 'true');
        $searchicon = html_writer::tag('i', '', $iconattrs);

        $formattrs = array('class' => 'search-input-form', 'action' => $CFG->wwwroot . '/search/index.php');
        $inputattrs = array('type' => 'text', 'name' => 'q', 'placeholder' => get_string('search', 'search'),
            'size' => 13, 'tabindex' => -1, 'id' => 'id_q_' . $identifier, 'class' => 'form-control');

        $contents = html_writer::tag('label', get_string('enteryoursearchquery', 'search'),
            array('for' => 'id_q_' . $identifier, 'class' => 'accesshide')) . html_writer::tag('input', '', $inputattrs);

        $btnclose = '<a class="close-search"><i class="fa fa-times"></i></a>';

        $searchinput = html_writer::tag('form', $contents . $btnclose, $formattrs);

        return html_writer::tag('div',
                                $searchicon . $searchinput,
                                array('class' => 'moove-search-input nav-link', 'id' => $identifier));
    }

    /**
     * The standard tags (meta tags, links to stylesheets and JavaScript, etc.)
     * that should be included in the <head> tag. Designed to be called in theme
     * layout.php files.
     *
     * @return string HTML fragment.
     */
    public function standard_head_html() {
        $output = parent::standard_head_html();

        // Add google analytics code.
        $googleanalyticscode = "<script>
                                    window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};
                                    ga.l=+new Date;ga('create', 'GOOGLE-ANALYTICS-CODE', 'auto');
                                    ga('send', 'pageview');
                                </script>
                                <script async src='https://www.google-analytics.com/analytics.js'></script>";

        $theme = theme_config::load('moove');

        if (!empty($theme->settings->googleanalytics)) {
            $output .= str_replace("GOOGLE-ANALYTICS-CODE", trim($theme->settings->googleanalytics), $googleanalyticscode);
        }

        return $output;
    }
    public function blocks($region, $classes = array(), $tag = 'aside') {
        $displayregion = $this->page->apply_theme_region_manipulations($region);
        $classes = (array)$classes;
        $classes[] = 'block-region';
        $attributes = array(
            'id' => 'block-region-'.preg_replace('#[^a-zA-Z0-9_\-]+#', '-', $displayregion),
            'class' => join(' ', $classes),
            'data-blockregion' => $displayregion,
            'data-droptarget' => '1'
        );
        if ($this->page->blocks->region_has_content($displayregion, $this)) {
            $content = $this->blocks_for_region($displayregion);
        } else {
            $content = '';
        }
        return html_writer::tag($tag, $content, $attributes);
    }
    public function full_header() {
        global $PAGE;

        $html = html_writer::start_tag('header', array('id' => 'page-header', 'class' => 'row'));
        $html .= html_writer::start_div('col-xs-12 p-a-1');
   
        $html .= html_writer::div($this->context_header_settings_menu(), 'pull-xs-right context-header-settings-menu');
        
        $pageheadingbutton = $this->page_heading_button();
        if (empty($PAGE->layout_options['nonavbar'])) {
            $html .= html_writer::start_div('clearfix w-100 pull-xs-left', array('id' => 'page-navbar'));
            $html .= html_writer::tag('div', $this->navbar(), array('class' => 'breadcrumb-nav'));
            $html .= html_writer::div($pageheadingbutton, 'breadcrumb-button pull-xs-right');
            $html .= html_writer::end_div();
        } else if ($pageheadingbutton) {
            $html .= html_writer::div($pageheadingbutton, 'breadcrumb-button pull-xs-right');
        }
        $html .= html_writer::tag('div', $this->course_header(), array('id' => 'course-header'));
        $html .= html_writer::end_div();
      
        $html .= html_writer::end_tag('header');
        return $html;
    }
    public function video_resume_box() {
        $content = '';
        $content .= '<div class="col-sm-6">
                        <div class="embed-responsive embed-responsive-4by3">
                          <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/Oj5E0U4QQVI?modestbranding=1&autohide=1&showinfo=0&controls=1"></iframe>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="img-wrapper">
                            <img class="img-responsive" src="http://app.itrackglobal.in/images/resume-db-banner.png">
                            <div class="img-overlay">
                                <button class="btn btn-md btn-success btn-analyzer">Analyze Resume</button>
                            </div>
                        </div>
                    </div>';
        return $content;
    }

    public function left_menubar(){
        global $DB,$CFG,$OUTPUT,$PAGE,$USER;
        $sidebar_content = '';
        $sidebar_content .= '<li>
                                    <a class="" href="'.$CFG->wwwroot.'/my">
                                        <i class="fa fa-tachometer"></i>
                                        <span class="text">Dashboard</span>
                                    </a>
                                </li>';
        if(is_siteadmin()){ //superadmin
            $sidebar_content .= $this->superadmin_menu();
        }elseif(user_has_role_assignment($USER->id, 9, SYSCONTEXTID)) { //account manager
            $sidebar_content .= $this->account_manager_menu();
        }elseif(user_has_role_assignment($USER->id, 10, SYSCONTEXTID)) { //partner admin
            $sidebar_content .= $this->partner_admin_menu();
        }elseif(user_has_role_assignment($USER->id, 11, SYSCONTEXTID)) { //training partner
            $sidebar_content .= $this->training_partner_menu();
        }elseif(user_has_role_assignment($USER->id, 12, SYSCONTEXTID)) { //professor 
            $sidebar_content .= $this->professor_menu();
        }else{ //student
            $sidebar_content .= $this->student_menu();
        }
        $sidebar_content .= '';
        $sidebar_content .= "<script>
                            var acc = document.getElementsByClassName('accordion');
                            var i;

                            for (i = 0; i < acc.length; i++) {
                              acc[i].addEventListener('click', function() {
                                this.classList.toggle('active');
                                var panel = this.nextElementSibling;
                                if (panel.style.maxHeight){
                                  panel.style.maxHeight = null;
                                } else {
                                  panel.style.maxHeight = panel.scrollHeight + 'px';
                                } 
                              });
                            }
                            </script>";
        return $sidebar_content;
    }

    public function superadmin_menu(){
        global $DB,$CFG,$OUTPUT,$PAGE,$USER;
        $sidebar_admin = '';
        $sidebar_admin .= '<li>
                                <a class="" href="'.$CFG->wwwroot.'/user/editadvanced.php?id=-1">
                                    <i class="fa fa-user"></i>
                                    <span class="text">Create Account Manager</span>
                                </a>
                            </li>
                            <li>
                                <a class="" href="#">
                                    <i class="fa fa-book"></i>
                                    <span class="text">Order Lists</span>
                                </a>
                            </li>
                            <li>
                                <a class="" href="#">
                                    <i class="fa fa-list-alt"></i>
                                    <span class="text">eLabs Promotion Rule</span>
                                </a>
                            </li>';
        $sidebar_admin .='<button class="accordion"><i class="fa fa-user"></i><span class="text">Common Functionality</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  iTrack eco value pack</a></li>
                                    <li><a href="#">&#128902;  News</a></li>
                                    <li><a href="#">&#128902;  Assign Premium Pack</a></li>
                                    <li><a href="#">&#128902;  Announcements</a></li>
                                </ul>
                            </div>

                            <button class="accordion"><i class="fa fa-book"></i><span class="text">Reports</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  Consolidated</a></li>
                                    <li><a href="#">&#128902;  Register and Package Reports</a></li>
                                    <li><a href="#">&#128902;  Webinar Subscriber</a></li>
                                    <li><a href="#">&#128902;  Special Program Reports</a></li>
                                    <li><a href="#">&#128902;  Login Reports</a></li>
                                </ul>
                            </div>';
        return $sidebar_admin;    
    }

    public function account_manager_menu(){
        global $DB,$CFG,$OUTPUT,$PAGE,$USER;
        $acc = $DB->get_records('partners',array('createdby'=>$USER->id));

        $sidebar_account = '';
        $sidebar_account .='<button class="accordion"><i class="fa fa-users"></i><span class="text">Create & Manage Users</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="'.$CFG->wwwroot.'/user/editadvanced.php?id=-1&role=partner">&#128902;  Create Partner</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/user/editadvanced.php?id=-1&role=mentor">&#128902;  Create Mentor</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/user/pauserlist.php">&#128902;  List of Partners</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/user/tpauserlist.php">&#128902;  List of Training Partners</a></li>
                                    <li><a href="#">&#128902;  List of Hiring Company</a></li>
                                    <li><a href="#">&#128902;  List of Mentors</a></li>
                                    <li><a href="#">&#128902;  New User Request</a></li>
                                    <li><a href="#">&#128902;  Assign Subscription Packages</a></li>
                                    <li><a href="#">&#128902;  Edit Validity</a></li>
                                </ul>
                            </div>

                            <button class="accordion"><i class="fa fa-laptop"></i><span class="text">Course Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  All Courses</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/course/subscribe_courses.php">&#128902;  Assign Courses</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/course/editcategory.php">&#128902;  Add Course Category</a></li>
                                </ul>
                            </div>
                            <button class="accordion"><i class="fa fa-language"></i><span class="text">Project Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="'.$CFG->wwwroot.'/local/eProjects/approved.php">&#128902;  All Projects</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/local/eProjects/project-attr.php">&#128902;  Project Attributes</a></li>
                                </ul>
                            </div>
                            <button class="accordion"><i class="fa fa-keyboard-o"></i><span class="text">Coupons</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  Partner Coupons</a></li>
                                    <li><a href="#">&#128902;  Training Partner Coupons</a></li>
                                </ul>
                            </div>
                            <li>
                                <a class="" href="#">
                                    <i class="fa fa-flag-checkered"></i>
                                    <span class="text">Add to Subscription</span>
                                </a>
                            </li>
                            <li>
                                <a class="" href="#">
                                    <i class="fa fa-flag"></i>
                                    <span class="text">Set eLabs Rule</span>
                                </a>
                            </li>
                            <button class="accordion"><i class="fa fa-video-camera"></i><span class="text">Video Conference Approval / Sessions</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  iKonnect Approvals</a></li>
                                    <li><a href="#">&#128902;  Zoom Meeting Approvals</a></li>
                                </ul>
                            </div>
                            <button class="accordion"><i class="fa fa-calculator"></i><span class="text">Common Functionality</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  3rd Party Assessments</a></li>
                                    <li><a href="#">&#128902;  Create eLabs</a></li>
                                    <li><a href="#">&#128902;  Upload Events</a></li>
                                    <li><a href="#">&#128902;  Upload iKonnect Sessions</a></li>
                                    <li><a href="#">&#128902;  Reset Or Switch Task</a></li>
                                </ul>
                            </div>
                            <button class="accordion"><i class="fa fa-thumbs-o-up"></i><span class="text">Mentor Reports</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  Mentors List</a></li>
                                    <li><a href="#">&#128902;  Mentors Calendar</a></li>
                                    <li><a href="#">&#128902;  Mentorship Request by Student</a></li>
                                    <li><a href="#">&#128902;  Mentor Rescheduled Request</a></li>
                                    <li><a href="#">&#128902;  Mentor Accepted Request</a></li>
                                    <li><a href="#">&#128902;  Mentor Rejected Request</a></li>
                                </ul>
                            </div>
                            <button class="accordion"><i class="fa fa-database"></i><span class="text">Reports</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  Consolidated</a></li>
                                    <li><a href="#">&#128902;  Register and Package Reports</a></li>
                                    <li><a href="#">&#128902;  TP Wise Login Reports</a></li>
                                    <li><a href="#">&#128902;  Consolidated Login Reports</a></li>
                                </ul>
                            </div>';
        return $sidebar_account;    
    }
    public function partner_admin_menu(){
        global $DB,$CFG,$OUTPUT,$PAGE,$USER;
        $arr = $DB->get_record('partners',array('userid'=>$USER->id),'permission');
        $pa_permission = explode(',',$arr->permission);
        $sidebar_partner = '';
        if($pa_permission[0] == 1){
            $sidebar_partner .='<button class="accordion"><i class="fa fa-user"></i><span class="text">User Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="'.$CFG->wwwroot.'/user/editadvanced.php?id=-1">&#128902;  Create User</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/user/tpauserlist.php">&#128902;  User List</a></li>
                                </ul>
                            </div>';
        }
        if($pa_permission[1] == 1){
            $sidebar_partner .='<button class="accordion"><i class="fa fa-book"></i><span class="text">News Management</span></button>
                                <div class="panel">
                                    <ul>
                                        <li><a href="#">&#128902;  Upload News</a></li>
                                        <li><a href="#">&#128902;  Lists of News</a></li>
                                    </ul>
                                </div>';
        }
        if($pa_permission[2] == 1){
            $sidebar_partner .='<button class="accordion"><i class="fa fa-list-alt"></i><span class="text">Reports & Views</span></button>
                                <div class="panel">
                                    <ul>
                                        <li><a href="#">&#128902;  Summary Reports</a></li>
                                        <li><a href="#">&#128902;  Training Partners Reports</a></li>
                                        <li><a href="#">&#128902;  Hiring Company Reports</a></li>
                                        <li><a href="#">&#128902;  Usage Reports</a></li>
                                    </ul>
                                </div>';
        }
        if($pa_permission[3] == 1){
            $sidebar_partner .='';
        }   

        return $sidebar_partner;    
    }
    public function training_partner_menu(){
        global $DB,$CFG,$OUTPUT,$PAGE,$USER;
        $creator = $DB->get_record('trainingpartners',array('userid'=>$USER->id),'createdby');
        $arr = $DB->get_record('partners',array('userid'=>$creator->createdby),'tp_permission');
        $tpa_permission = explode(',',$arr->tp_permission);
        $sidebar_training_partner = '';
        if($tpa_permission[0] == 1){
            $sidebar_training_partner .='<button class="accordion"><i class="fa fa-users"></i><span class="text">User Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="'.$CFG->wwwroot.'/user/tpauserlist.php">&#128902;  User List</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/admin/tool/uploaduser/index.php">&#128902;  User Bulk Upload</a></li>
                                </ul>
                            </div>';
        }
        if($tpa_permission[1] == 1){
            $sidebar_training_partner .='<button class="accordion"><i class="fa fa-btc"></i><span class="text">Batch Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="'.$CFG->wwwroot.'/local/course_batches/batch.php">&#128902;  Create Batch</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/local/course_batches/batch_enrolment.php">&#128902;  Add Students To Batch</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/local/course_batches/batchlist.php">&#128902;  Batch List</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/local/course_batches/migrate.php">&#128902;  Migrate Batch</a></li>
                                </ul>
                            </div>';
        }
        if($tpa_permission[2] == 1){
            $sidebar_training_partner .='<button class="accordion"><i class="fa fa-book"></i><span class="text">Course Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="'.$CFG->wwwroot.'/course/edit.php">&#128902;  Create Course</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/course/courselist.php">&#128902;  Course List</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/local/course_batches/batch_to_course.php">&#128902;  Assign Course To Batch</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/local/course_batches/course_to_professor.php">&#128902;  Assign Course To Professor</a></li>
                                </ul>
                            </div>';
        }   
        if($tpa_permission[3] == 1){
            $sidebar_training_partner .='<button class="accordion"><i class="fa fa-code-fork"></i><span class="text">Project Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  Manage Component</a></li>
                                    <li><a href="#">&#128902;  Manage Project</a></li>
                                </ul>
                            </div>';
        }
        if($tpa_permission[4] == 1){
            $sidebar_training_partner .='<button class="accordion"><i class="fa fa-tasks"></i><span class="text">Task Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  Manage Task</a></li>
                                    <li><a href="#">&#128902;  Task List</a></li>
                                    <li><a href="#">&#128902;  BulkUpload Task</a></li>
                                    <li><a href="#">&#128902;  BulkAssign Task</a></li>
                                </ul>
                            </div>';
        }
        if($tpa_permission[5] == 1){
            $sidebar_training_partner .='<button class="accordion"><i class="fa fa-database"></i><span class="text">Reports & Views</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  Academic Reports</a></li>
                                    <li><a href="#">&#128902;  Academic Progress Reports</a></li>
                                    <li><a href="#">&#128902;  Non-Academic Reports</a></li>
                                    <li><a href="#">&#128902;  Non-Academic Progress Reports</a></li>
                                    <li><a href="#">&#128902;  Usage Reports</a></li>
                                </ul>
                            </div>';
        }
        if($tpa_permission[6] == 1){
            $sidebar_training_partner .='';
        }
        return $sidebar_training_partner;    
    }
    public function professor_menu(){
        global $DB,$CFG,$OUTPUT,$PAGE,$USER;
        $sidebar_professors = '';
        if($DB->record_exists('tp_useruploads',array('userid'=>$USER->id))){
            $creator_tp = $DB->get_record('tp_useruploads',array('userid'=>$USER->id),'creatorid');
        }
        $creator_pa = $DB->get_record('trainingpartners',array('userid'=>$creator_tp->creatorid  ),'createdby');
        $arr = $DB->get_record('partners',array('userid'=>$creator_pa->createdby),'prof_permission');
        $prof_permission = explode(',',$arr->prof_permission);
        
        if($prof_permission[0] == 1){
            $sidebar_professors .='<button class="accordion"><i class="fa fa-search-plus"></i><span class="text">Batch Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  Batch List</a></li>
                                    <li><a href="#">&#128902;  Record Attendance</a></li>
                                </ul>
                            </div>';
        }
        if($prof_permission[1] == 1){
            $sidebar_professors .='<li>
                                    <a class="" href="'.$CFG->wwwroot.'/my/courses.php">
                                        <i class="fa fa-book"></i>
                                        <span class="text">My Courses</span>
                                    </a>
                                </li>';
        }
        if($prof_permission[2] == 1){
            $sidebar_professors .='<button class="accordion"><i class="fa fa-laptop"></i><span class="text">My eLabs</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="'.$CFG->wwwroot.'/local/eLabs/addlabs.php">&#128902;  Add Labs</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/local/eLabs/assign_labs.php">&#128902;  Assign Labs</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/local/eLabs/lablists.php">&#128902;  List of Labs</a></li>
                                    <li><a href="#">&#128902;  Evaluate Labs</a></li>
                                </ul>
                            </div>';
        }
        if($prof_permission[3] == 1){

            $manage_component = $CFG->wwwroot.'/local/eProjects/add_component.php';
            $manage_project = $CFG->wwwroot.'/local/eProjects/add_project.php';
            $project_request = $CFG->wwwroot.'/local/eProjects/request.php';
            $evaluate_project = $CFG->wwwroot.'/local/eProjects/evaluate.php';
            $sidebar_professors .='<button class="accordion"><i class="fa fa-code-fork"></i><span class="text">Project Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="'.$manage_component.'">&#128902;  Manage Component</a></li>
                                    <li><a href="'.$manage_project.'">&#128902;  Manage Project</a></li>
                                    <li><a href="'.$project_request.'">&#128902;  Project Request</a></li>
                                    <li><a href="'.$evaluate_project.'">&#128902;  Evaluate Project</a></li>
                                </ul>
                            </div>';
        }
        if($prof_permission[4] == 1){
            $sidebar_professors .='<li>
                                    <a class="" href="'.$CFG->wwwroot.'/my">
                                        <i class="fa fa-pencil-square-o"></i>
                                        <span class="text">My Assessments</span>
                                    </a>
                                </li>';
        }
        if($prof_permission[5] == 1){
            $sidebar_professors .='<button class="accordion"><i class="fa fa-tasks"></i><span class="text">Task Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  List of Tasks</a></li>
                                    <li><a href="#">&#128902;  Evaluate Tasks</a></li>
                                </ul>
                            </div>';
        }
        if($prof_permission[6] == 1){
            $sidebar_professors .='<button class="accordion"><i class="fa fa-comments-o"></i><span class="text">Video Conferences</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  iKonnect</a></li>
                                    <li><a href="#">&#128902;  Zoom</a></li>
                                </ul>
                            </div>';
        }
        if($prof_permission[7] == 1){
            $sidebar_professors .='<button class="accordion"><i class="fa fa-database"></i><span class="text">Reports</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  Consolidated Report</a></li>
                                    <li><a href="#">&#128902;  Attendance Report</a></li>
                                    <li><a href="#">&#128902;  Prescribed Course Reports</a></li>
                                </ul>
                            </div>';
        }
        
        
        return $sidebar_professors;    
    }
    public function student_menu(){
        global $DB,$CFG,$OUTPUT,$PAGE,$USER;
        $sidebar_students = '';
        $stud_permission = '';
        if($DB->record_exists('tp_useruploads',array('userid'=>$USER->id))){
            $creator_tp = $DB->get_record('tp_useruploads',array('userid'=>$USER->id),'creatorid');
            $creator_pa = $DB->get_record('trainingpartners',array('userid'=>$creator_tp->creatorid  ),'createdby');
            $arr = $DB->get_record('partners',array('userid'=>$creator_pa->createdby),'stud_permission');
            $stud_permission = explode(',',$arr->stud_permission);
        
            if($stud_permission[9] == 1){
                $sidebar_students ='<li>
                                    <a class="" href="'.$CFG->wwwroot.'/my/account.php">
                                        <i class="fa fa-shopping-cart"></i>
                                        <span class="text">My Account</span>
                                    </a>
                                </li>';
            }
            if($stud_permission[0] == 1){
                $sidebar_students .='<li>
                                        <a class="" href="'.$CFG->wwwroot.'/my/courses.php">
                                            <i class="fa fa-book"></i>
                                            <span class="text">My Courses</span>
                                        </a>
                                    </li>';
            }
            if($stud_permission[1] == 1){
                $sidebar_students .='<button class="accordion"><i class="fa fa-laptop"></i><span class="text">My eLabs</span></button>
                                <div class="panel">
                                    <ul>
                                        <li><a href="'.$CFG->wwwroot.'/local/eLabs/myelabs.php">&#128902;  My Labs</a></li>
                                        <li><a href="#">&#128902;  My lab feedbacks</a></li>
                                        <li><a href="#">&#128902;  My lab reports Group</a></li>
                                    </ul>
                                </div>';
            }
            if($stud_permission[2] == 1){
                $sidebar_students .='<button class="accordion"><i class="fa fa-code-fork"></i><span class="text">My eProjects</span></button>
                                <div class="panel">
                                    <ul>
                                        <li><a href="'.$CFG->wwwroot.'/local/eProjects/eprojects.php">&#128902;  My Projects</a></li>
                                        <li><a href="'.$CFG->wwwroot.'/local/eProjects/group.php">&#128902;  Manage Group</a></li>
                                        <li><a href="'.$CFG->wwwroot.'/local/eProjects/grouplist.php">&#128902;  Assign Group</a></li>
                                        <li><a href="'.$CFG->wwwroot.'/local/eProjects/search-project.php">&#128902;  Search Projects</a></li>
                                        <li><a href="'.$CFG->wwwroot.'/local/eProjects/accesspeer.php">&#128902;  Access Peers</a></li>
                                    </ul>
                                </div>';
            }
            if($stud_permission[3] == 1){
                $sidebar_students .='<li>
                                        <a class="" href="'.$CFG->wwwroot.'/my">
                                            <i class="fa fa-pencil-square-o"></i>
                                            <span class="text">My Assessments</span>
                                        </a>
                                    </li>';
            }
            if($stud_permission[4] == 1){
                $sidebar_students .='<li>
                                        <a class="" href="'.$CFG->wwwroot.'/my">
                                            <i class="fa fa-tasks"></i>
                                            <span class="text">My Tasks</span>
                                        </a>
                                    </li>';
            }
            if($stud_permission[8] == 1){
                $sidebar_students .='<button class="accordion"><i class="fa fa-street-view"></i><span class="text">My Mentors</span></button>
                                <div class="panel">
                                    <ul>
                                        <li><a href="#">&#128902;  Search Mentors</a></li>
                                        <li><a href="#">&#128902;  View Schedules</a></li>
                                        <li><a href="#">&#128902;  Mentoring Feedback</a></li>
                                    </ul>
                                </div>';
            }
            if($stud_permission[7] == 1){
                $sidebar_students .='<button class="accordion"><i class="fa fa-comments-o"></i><span class="text">Video Conferences</span></button>
                                <div class="panel">
                                    <ul>
                                        <li><a href="#">&#128902;  iKonnect</a></li>
                                        <li><a href="#">&#128902;  Zoom</a></li>
                                    </ul>
                                </div>';
            }
            if($stud_permission[6] == 1){
                $sidebar_students .='<li>
                                        <a class="" href="'.$CFG->wwwroot.'/my">
                                            <i class="fa fa-mortar-board"></i>
                                            <span class="text">Resume Analyzer</span>
                                        </a>
                                    </li>';
            }
            if($stud_permission[5] == 1){
                $sidebar_students .='<button class="accordion"><i class="fa fa-search-plus"></i><span class="text">Job Bridge</span></button>
                                <div class="panel">
                                    <ul>
                                        <li><a href="#">&#128902;  Apply for Jobs</a></li>
                                        <li><a href="#">&#128902;  Apply for Internships</a></li>
                                        <li><a href="#">&#128902;  Career Guidance</a></li>
                                        <li><a href="#">&#128902;  Resume Building</a></li>
                                    </ul>
                                </div>';
            }
        }
        $sidebar_students .='<li>
                                <a class="" href="'.$CFG->wwwroot.'/my">
                                    <i class="fa fa-university"></i>
                                    <span class="text">Higher Studies</span>
                                </a>
                            </li>';
        return $sidebar_students;    
    }
    public function itrack_usermenu(){
        global $USER,$DB,$CFG;
        $menu = '';
        $menu .='<div class="dropdown1-content">
                    <a href="'.$CFG->wwwroot.'/user/profile.php?id='.$USER->id.'"><i class=" fa fa-suitcase"></i>Profile</a>
                    <a href="'.$CFG->wwwroot.'/admin/search.php"><i class="fa fa-cog"></i> Settings</a>
                    <a href="'.$CFG->wwwroot.'/login/logout.php?sesskey='.sesskey().'"><i class="fa fa-key"></i>Log out</a>
                </div>';
        return $menu;
    }
    public function itrack_username(){
        global $USER,$DB,$CFG;
        $username = '';
        $user = $DB->get_record('user',array('id'=>$USER->id),'id,firstname,username');
        $username = $user->firstname;
        return $username;
    }
}
