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
 * Parent theme: Bootstrapbase by Bas navbar-brands
 * Built on: Essential by Julian Ridden
 *
 * @package   theme_defaultlms
 * @copyright 2014 redPIthemes
 *
 */

$settings = null;

defined('MOODLE_INTERNAL') || die;

$ADMIN->add('themes', new admin_category('theme_defaultlms', 'Theme-defaultlms'));
	
	// "settings general" settingpage
	$temp = new admin_settingpage('theme_defaultlms_general',  get_string('settings_general', 'theme_defaultlms'));
		
	// Logo file setting.
    $name = 'theme_defaultlms/logo';
    $title = get_string('logo', 'theme_defaultlms');
    $description = get_string('logodesc', 'theme_defaultlms');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Fixed or Variable Width.
    $name = 'theme_defaultlms/pagewidth';
    $title = get_string('pagewidth', 'theme_defaultlms');
    $description = get_string('pagewidthdesc', 'theme_defaultlms');
    $default = 1600;
    $choices = array(1600=>get_string('boxed_wide','theme_defaultlms'), 1000=>get_string('boxed_narrow','theme_defaultlms'), 90=>get_string('boxed_variable','theme_defaultlms'), 100=>get_string('full_wide','theme_defaultlms'));
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // Custom or standard block layout.
    $name = 'theme_defaultlms/layout';
    $title = get_string('layout', 'theme_defaultlms');
    $description = get_string('layoutdesc', 'theme_defaultlms');
    $default = false;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	   
    // Footnote setting.
    $name = 'theme_defaultlms/footnote';
    $title = get_string('footnote', 'theme_defaultlms');
    $description = get_string('footnotedesc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
    // Custom CSS file.
    $name = 'theme_defaultlms/customcss';
    $title = get_string('customcss', 'theme_defaultlms');
    $description = get_string('customcssdesc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	$ADMIN->add('theme_defaultlms', $temp);
	
	// "settings background" settingpage
	$temp = new admin_settingpage('theme_defaultlms_background',  get_string('settings_background', 'theme_defaultlms'));
	
	// list with provides backgrounds 
    $name = 'theme_defaultlms/list_bg';
    $title = get_string('list_bg', 'theme_defaultlms');
    $description = get_string('list_bg_desc', 'theme_defaultlms');
    $default = '0';
    $choices = array(
		'0'=>'Country Road',
		'1'=>'Bokeh Background',
		'2'=>'Blurred Background I',
		'3'=>'Blurred Background II',
		'4'=>'Blurred Background III',
		'5'=>'Cream Pixels (Pattern)',
		'6'=>'MochaGrunge (Pattern)',
		'7'=>'Skulls (Pattern)',
		'8'=>'SOS (Pattern)',
		'9'=>'Squairy Light (Pattern)',
		'10'=>'Subtle White Feathers (Pattern)',
		'11'=>'Tweed (Pattern)',
		'12'=>'Wet Snow (Pattern)');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

	// upload background image.
    $name = 'theme_defaultlms/pagebackground';
    $title = get_string('pagebackground', 'theme_defaultlms');
    $description = get_string('pagebackgrounddesc', 'theme_defaultlms');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'pagebackground');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// bg repeat.
	$name = 'theme_defaultlms/page_bg_repeat';
    $title = get_string('page_bg_repeat', 'theme_defaultlms');
    $description = get_string('page_bg_repeat_desc', 'theme_defaultlms');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    $ADMIN->add('theme_defaultlms', $temp);
	
	// "settings colors" settingpage
	$temp = new admin_settingpage('theme_defaultlms_colors',  get_string('settings_colors', 'theme_defaultlms'));
	
    // Main theme color setting.
    $name = 'theme_defaultlms/maincolor';
    $title = get_string('maincolor', 'theme_defaultlms');
    $description = get_string('maincolordesc', 'theme_defaultlms');
    $default = '#e2a500';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Main theme Hover color setting.
    $name = 'theme_defaultlms/mainhovercolor';
    $title = get_string('mainhovercolor', 'theme_defaultlms');
    $description = get_string('mainhovercolordesc', 'theme_defaultlms');
    $default = '#c48f00';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Link color setting.
    $name = 'theme_defaultlms/linkcolor';
    $title = get_string('linkcolor', 'theme_defaultlms');
    $description = get_string('linkcolordesc', 'theme_defaultlms');
    $default = '#966b00';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Default Button color setting.
    $name = 'theme_defaultlms/def_buttoncolor';
    $title = get_string('def_buttoncolor', 'theme_defaultlms');
    $description = get_string('def_buttoncolordesc', 'theme_defaultlms');
    $default = '#8ec63f';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Default Button Hover color setting.
    $name = 'theme_defaultlms/def_buttonhovercolor';
    $title = get_string('def_buttonhovercolor', 'theme_defaultlms');
    $description = get_string('def_buttonhovercolordesc', 'theme_defaultlms');
    $default = '#77ae29';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Menu 1. Level color setting.
    $name = 'theme_defaultlms/menufirstlevelcolor';
    $title = get_string('menufirstlevelcolor', 'theme_defaultlms');
    $description = get_string('menufirstlevelcolordesc', 'theme_defaultlms');
    $default = '#323A45';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Menu 1. Level Links color setting.
    $name = 'theme_defaultlms/menufirstlevel_linkcolor';
    $title = get_string('menufirstlevel_linkcolor', 'theme_defaultlms');
    $description = get_string('menufirstlevel_linkcolordesc', 'theme_defaultlms');
    $default = '#ffffff';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Menu 2. Level color setting.
    $name = 'theme_defaultlms/menusecondlevelcolor';
    $title = get_string('menusecondlevelcolor', 'theme_defaultlms');
    $description = get_string('menusecondlevelcolordesc', 'theme_defaultlms');
    $default = '#f4f4f4';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Menu 2. Level Links color.
    $name = 'theme_defaultlms/menusecondlevel_linkcolor';
    $title = get_string('menusecondlevel_linkcolor', 'theme_defaultlms');
    $description = get_string('menusecondlevel_linkcolordesc', 'theme_defaultlms');
    $default = '#444444';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Footer color setting.
    $name = 'theme_defaultlms/footercolor';
    $title = get_string('footercolor', 'theme_defaultlms');
    $description = get_string('footercolordesc', 'theme_defaultlms');
    $default = '#323A45';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Footer Headings color setting.
    $name = 'theme_defaultlms/footerheadingcolor';
    $title = get_string('footerheadingcolor', 'theme_defaultlms');
    $description = get_string('footerheadingcolordesc', 'theme_defaultlms');
    $default = '#f9f9f9';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Footer Text color setting.
    $name = 'theme_defaultlms/footertextcolor';
    $title = get_string('footertextcolor', 'theme_defaultlms');
    $description = get_string('footertextcolordesc', 'theme_defaultlms');
    $default = '#bdc3c7';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Copyright color setting.
    $name = 'theme_defaultlms/copyrightcolor';
    $title = get_string('copyrightcolor', 'theme_defaultlms');
    $description = get_string('copyrightcolordesc', 'theme_defaultlms');
    $default = '#292F38';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Copyright color setting.
    $name = 'theme_defaultlms/copyright_textcolor';
    $title = get_string('copyright_textcolor', 'theme_defaultlms');
    $description = get_string('copyright_textcolordesc', 'theme_defaultlms');
    $default = '#bdc3c7';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);	
	
	$ADMIN->add('theme_defaultlms', $temp); 
	
	// "settings socials" settingpage
	$temp = new admin_settingpage('theme_defaultlms_socials',  get_string('settings_socials', 'theme_defaultlms'));
	$temp->add(new admin_setting_heading('theme_defaultlms_socials', get_string('socialsheadingsub', 'theme_defaultlms'),
            format_text(get_string('socialsdesc' , 'theme_defaultlms'), FORMAT_MARKDOWN)));
    
    // Website url setting.
    $name = 'theme_defaultlms/website';
    $title = get_string('website', 'theme_defaultlms');
    $description = get_string('websitedesc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Mail setting.
    $name = 'theme_defaultlms/socials_mail';
    $title = get_string('socials_mail', 'theme_defaultlms');
    $description = get_string('socials_mail_desc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // Facebook url setting.
    $name = 'theme_defaultlms/facebook';
    $title = get_string('facebook', 'theme_defaultlms');
    $description = get_string('facebookdesc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // Flickr url setting.
    $name = 'theme_defaultlms/flickr';
    $title = get_string('flickr', 'theme_defaultlms');
    $description = get_string('flickrdesc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Twitter url setting.
    $name = 'theme_defaultlms/twitter';
    $title = get_string('twitter', 'theme_defaultlms');
    $description = get_string('twitterdesc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Google+ url setting.
    $name = 'theme_defaultlms/googleplus';
    $title = get_string('googleplus', 'theme_defaultlms');
    $description = get_string('googleplusdesc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Pinterest url setting.
    $name = 'theme_defaultlms/pinterest';
    $title = get_string('pinterest', 'theme_defaultlms');
    $description = get_string('pinterestdesc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // Instagram url setting.
    $name = 'theme_defaultlms/instagram';
    $title = get_string('instagram', 'theme_defaultlms');
    $description = get_string('instagramdesc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
    
    // YouTube url setting.
    $name = 'theme_defaultlms/youtube';
    $title = get_string('youtube', 'theme_defaultlms');
    $description = get_string('youtubedesc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// social icons color setting.
    $name = 'theme_defaultlms/socials_color';
    $title = get_string('socials_color', 'theme_defaultlms');
    $description = get_string('socials_color_desc', 'theme_defaultlms');
    $default = '#a9a9a9';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// social icons position 
    $name = 'theme_defaultlms/socials_position';
    $title = get_string('socials_position', 'theme_defaultlms');
    $description = get_string('socials_position_desc', 'theme_defaultlms');
    $default = '0';
    $choices = array(
		'0'=>'footer',
		'1'=>'header');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	$ADMIN->add('theme_defaultlms', $temp); 
	
	// "settings fonts" settingpage
	$temp = new admin_settingpage('theme_defaultlms_fonts',  get_string('settings_fonts', 'theme_defaultlms'));
	
	$name = 'theme_defaultlms/font_body';
    $title = get_string('fontselect_body' , 'theme_defaultlms');
    $description = get_string('fontselectdesc_body', 'theme_defaultlms');
    $default = '1';
    $choices = array(
    	'1'=>'Open Sans',
		'2'=>'Arimo',
		'3'=>'Arvo',
		'4'=>'Bree Serif',
		'5'=>'Cabin',
		'6'=>'Cantata One',
		'7'=>'Crimson Text',
		'8'=>'Droid Sans',
		'9'=>'Droid Serif',
		'10'=>'Gudea',
		'11'=>'Imprima',
		'12'=>'Lekton',
		'13'=>'Nixie One',
		'14'=>'Nobile',
		'15'=>'Playfair Display',
		'16'=>'Pontano Sans',
		'17'=>'PT Sans',
    	'18'=>'Raleway', 
		'19'=>'Ubuntu',
    	'20'=>'Vollkorn');
	 			
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
    $name = 'theme_defaultlms/font_heading';
    $title = get_string('fontselect_heading' , 'theme_defaultlms');
    $description = get_string('fontselectdesc_heading', 'theme_defaultlms');
    $default = '1';
    $choices = array(			
		'1'=>'Open Sans',
		'2'=>'Abril Fatface',
		'3'=>'Arimo',
		'4'=>'Arvo',
		'5'=>'Bevan', 
		'6'=>'Bree Serif',
		'7'=>'Cabin',
		'8'=>'Cantata One',
		'9'=>'Crimson Text',
		'10'=>'Droid Sans',
		'11'=>'Droid Serif',
		'12'=>'Gudea',
		'13'=>'Imprima',
		'14'=>'Josefin Sans',
		'15'=>'Lekton',
		'16'=>'Lobster',
		'17'=>'Nixie One',
		'18'=>'Nobile',
		'19'=>'Pacifico',
		'20'=>'Playfair Display',
		'21'=>'Pontano Sans',
		'22'=>'PT Sans',
    	'23'=>'Raleway', 
		'24'=>'Sansita One',
		'25'=>'Ubuntu',
    	'26'=>'Vollkorn');

    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	$ADMIN->add('theme_defaultlms', $temp); 

	// "settings slider" settingpage
	$temp = new admin_settingpage('theme_defaultlms_slider',  get_string('settings_slider', 'theme_defaultlms'));
    $temp->add(new admin_setting_heading('theme_defaultlms_slider', get_string('slideshowheadingsub', 'theme_defaultlms'),
            format_text(get_string('slideshowdesc' , 'theme_defaultlms'), FORMAT_MARKDOWN)));

    /*
     * Slide 1
     */	
	 $temp->add(new admin_setting_heading('theme_defaultlms_slider_slide1', get_string('slideshow_slide1', 'theme_defaultlms'),NULL));
	
    // Image.
    $name = 'theme_defaultlms/slide1image';
    $title = get_string('slideimage', 'theme_defaultlms');
    $description = get_string('slideimagedesc', 'theme_defaultlms');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'slide1image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Title.
    $name = 'theme_defaultlms/slide1';
    $title = get_string('slidetitle', 'theme_defaultlms');
    $description = get_string('slidetitledesc', 'theme_defaultlms');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $default = '';
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);    

    // Caption.
    $name = 'theme_defaultlms/slide1caption';
    $title = get_string('slidecaption', 'theme_defaultlms');
    $description = get_string('slidecaptiondesc', 'theme_defaultlms');
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// URL.
	$name = 'theme_defaultlms/slide1_url';
    $title = get_string('slide_url', 'theme_defaultlms');
    $description = get_string('slide_url_desc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    /*
     * Slide 2
     */
	 $temp->add(new admin_setting_heading('theme_defaultlms_slider_slide2', get_string('slideshow_slide2', 'theme_defaultlms'),NULL));

    // Image.
    $name = 'theme_defaultlms/slide2image';
    $title = get_string('slideimage', 'theme_defaultlms');
    $description = get_string('slideimagedesc', 'theme_defaultlms');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'slide2image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Title.
    $name = 'theme_defaultlms/slide2';
    $title = get_string('slidetitle', 'theme_defaultlms');
    $description = get_string('slidetitledesc', 'theme_defaultlms');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $default = '';
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);    

    // Caption.
    $name = 'theme_defaultlms/slide2caption';
    $title = get_string('slidecaption', 'theme_defaultlms');
    $description = get_string('slidecaptiondesc', 'theme_defaultlms');
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// URL.
	$name = 'theme_defaultlms/slide2_url';
    $title = get_string('slide_url', 'theme_defaultlms');
    $description = get_string('slide_url_desc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    /*
     * Slide 3
     */
	 $temp->add(new admin_setting_heading('theme_defaultlms_slider_slide3', get_string('slideshow_slide3', 'theme_defaultlms'),NULL));

    // Image.
    $name = 'theme_defaultlms/slide3image';
    $title = get_string('slideimage', 'theme_defaultlms');
    $description = get_string('slideimagedesc', 'theme_defaultlms');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'slide3image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Title.
    $name = 'theme_defaultlms/slide3';
    $title = get_string('slidetitle', 'theme_defaultlms');
    $description = get_string('slidetitledesc', 'theme_defaultlms');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $default = '';
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);    

    // Caption.
    $name = 'theme_defaultlms/slide3caption';
    $title = get_string('slidecaption', 'theme_defaultlms');
    $description = get_string('slidecaptiondesc', 'theme_defaultlms');
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// URL.
	$name = 'theme_defaultlms/slide3_url';
    $title = get_string('slide_url', 'theme_defaultlms');
    $description = get_string('slide_url_desc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    /*
     * Slide 4
     */
	 $temp->add(new admin_setting_heading('theme_defaultlms_slider_slide4', get_string('slideshow_slide4', 'theme_defaultlms'),NULL));

    // Image.
    $name = 'theme_defaultlms/slide4image';
    $title = get_string('slideimage', 'theme_defaultlms');
    $description = get_string('slideimagedesc', 'theme_defaultlms');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'slide4image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Title.
    $name = 'theme_defaultlms/slide4';
    $title = get_string('slidetitle', 'theme_defaultlms');
    $description = get_string('slidetitledesc', 'theme_defaultlms');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $default = '';
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);    

    // Caption.
    $name = 'theme_defaultlms/slide4caption';
    $title = get_string('slidecaption', 'theme_defaultlms');
    $description = get_string('slidecaptiondesc', 'theme_defaultlms');
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// URL.
	$name = 'theme_defaultlms/slide4_url';
    $title = get_string('slide_url', 'theme_defaultlms');
    $description = get_string('slide_url_desc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    /*
     * Slide 5
     */
	 $temp->add(new admin_setting_heading('theme_defaultlms_slider_slide5', get_string('slideshow_slide5', 'theme_defaultlms'),NULL));

    // Image.
    $name = 'theme_defaultlms/slide5image';
    $title = get_string('slideimage', 'theme_defaultlms');
    $description = get_string('slideimagedesc', 'theme_defaultlms');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'slide5image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Title.
    $name = 'theme_defaultlms/slide5';
    $title = get_string('slidetitle', 'theme_defaultlms');
    $description = get_string('slidetitledesc', 'theme_defaultlms');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $default = '';
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);    

    // Caption.
    $name = 'theme_defaultlms/slide5caption';
    $title = get_string('slidecaption', 'theme_defaultlms');
    $description = get_string('slidecaptiondesc', 'theme_defaultlms');
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// URL.
	$name = 'theme_defaultlms/slide5_url';
    $title = get_string('slide_url', 'theme_defaultlms');
    $description = get_string('slide_url_desc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	/*
     * Options
     */
	 $temp->add(new admin_setting_heading('theme_defaultlms_slider_options', get_string('slideshow_options', 'theme_defaultlms'),NULL));
	
    // Slideshow Pattern 
    $name = 'theme_defaultlms/slideshowpattern';
    $title = get_string('slideshowpattern', 'theme_defaultlms');
    $description = get_string('slideshowpatterndesc', 'theme_defaultlms');
    $default = '0';
    $choices = array(
		'0'=>'none',
		'1'=>'pattern1',
		'2'=>'pattern2',
		'3'=>'pattern3',
		'4'=>'pattern4');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Slidshow AutoAdvance
	$name = 'theme_defaultlms/slideshow_advance';
    $title = get_string('slideshow_advance', 'theme_defaultlms');
    $description = get_string('slideshow_advance_desc', 'theme_defaultlms');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 1);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Slidshow Navigation
	$name = 'theme_defaultlms/slideshow_nav';
    $title = get_string('slideshow_nav', 'theme_defaultlms');
    $description = get_string('slideshow_nav_desc', 'theme_defaultlms');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 1);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Slideshow Loader 
    $name = 'theme_defaultlms/slideshow_loader';
    $title = get_string('slideshow_loader', 'theme_defaultlms');
    $description = get_string('slideshow_loader_desc', 'theme_defaultlms');
    $default = '0';
    $choices = array(
		'0'=>'bar',
		'1'=>'pie',
		'2'=>'none');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Slideshow Image FX
	$name = 'theme_defaultlms/slideshow_imgfx';
	$title = get_string('slideshow_imgfx','theme_defaultlms');
	$description = get_string('slideshow_imgfx_desc', 'theme_defaultlms');
	$setting = new admin_setting_configtext($name, $title, $description, 'random', PARAM_URL);
	$temp->add($setting);
	
	// Slideshow Text FX
	$name = 'theme_defaultlms/slideshow_txtfx';
	$title = get_string('slideshow_txtfx','theme_defaultlms');
	$description = get_string('slideshow_txtfx_desc', 'theme_defaultlms');
	$setting = new admin_setting_configtext($name, $title, $description, 'moveFromLeft', PARAM_URL);
	$temp->add($setting);
	
	$ADMIN->add('theme_defaultlms', $temp);
	
	// "frontpage carousel" settingpage 
    $temp = new admin_settingpage('theme_defaultlms_carousel', get_string('settings_carousel', 'theme_defaultlms'));
    $temp->add(new admin_setting_heading('theme_defaultlms_carousel', get_string('carouselheadingsub', 'theme_defaultlms'),
            format_text(get_string('carouseldesc' , 'theme_defaultlms'), FORMAT_MARKDOWN)));
    
    // Position
    $name = 'theme_defaultlms/carousel_position';
    $title = get_string('carousel_position', 'theme_defaultlms');
    $description = get_string('carousel_positiondesc', 'theme_defaultlms');
	$default = '1';
    $choices = array(
		'0'=>'top',
		'1'=>'bottom');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Heading
    $name = 'theme_defaultlms/carousel_h';
    $title = get_string('carousel_h', 'theme_defaultlms');
    $description = get_string('carousel_h_desc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default, PARAM_TEXT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Heading Style
    $name = 'theme_defaultlms/carousel_hi';
    $title = get_string('carousel_hi', 'theme_defaultlms');
    $description = get_string('carousel_hi_desc', 'theme_defaultlms');
	$default = '3';
    $choices = array(
		'1'=>'Heading h1',
		'2'=>'Heading h2',
		'3'=>'Heading h3',
		'4'=>'Heading h4',
		'5'=>'Heading h5',
		'6'=>'Heading h6');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Additional HTML
	$name = 'theme_defaultlms/carousel_add_html';
    $title = get_string('carousel_add_html', 'theme_defaultlms');
    $description = get_string('carousel_add_html_desc', 'theme_defaultlms');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);
	
	// Number of slides.
    $name = 'theme_defaultlms/carousel_slides';
    $title = get_string('carousel_slides', 'theme_defaultlms');
    $description = get_string('carousel_slides_desc', 'theme_defaultlms');
    $default = 4;
    $choices = array(
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5',
        6 => '6',
        7 => '7',
        8 => '8',
        9 => '9',
        10 => '10',
        11 => '11',
        12 => '12',
        13 => '13',
        14 => '14',
        15 => '15',
        16 => '16'
    );
    $temp->add(new admin_setting_configselect($name, $title, $description, $default, $choices));

    $numberofslides = get_config('theme_defaultlms', 'carousel_slides');
    for ($i = 1; $i <= $numberofslides; $i++) {
		// Image.
        $name = 'theme_defaultlms/carousel_image_'.$i;
        $title = get_string('carousel_image', 'theme_defaultlms');
        $description = get_string('carousel_imagedesc', 'theme_defaultlms');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'carousel_image_'.$i);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);

        // Caption Heading.
        $name = 'theme_defaultlms/carousel_heading_'.$i;
        $title = get_string('carousel_heading', 'theme_defaultlms');
        $description = get_string('carousel_heading_desc', 'theme_defaultlms');
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);

        // Caption text.
        $name = 'theme_defaultlms/carousel_caption_'.$i;
        $title = get_string('carousel_caption', 'theme_defaultlms');
        $description = get_string('carousel_caption_desc', 'theme_defaultlms');
        $default = '';
        $setting = new admin_setting_configtextarea($name, $title, $description, $default, PARAM_TEXT);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);

        // URL.
        $name = 'theme_defaultlms/carousel_url_'.$i;
        $title = get_string('carousel_url', 'theme_defaultlms');
        $description = get_string('carousel_urldesc', 'theme_defaultlms');
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);
		
		// Button Text.
        $name = 'theme_defaultlms/carousel_btntext_'.$i;
        $title = get_string('carousel_btntext', 'theme_defaultlms');
        $description = get_string('carousel_btntextdesc', 'theme_defaultlms');
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);

        // Color
        $name = 'theme_defaultlms/carousel_color_'.$i;
        $title = get_string('carousel_color', 'theme_defaultlms');
        $description = get_string('carousel_colordesc', 'theme_defaultlms');
		$default = '0';
    	$choices = array(
			'0'=>'green',
			'1'=>'purple',
			'2'=>'orange',
			'3'=>'lightblue',
			'4'=>'yellow',
			'5'=>'turquoise');
    	$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);
    }
    $ADMIN->add('theme_defaultlms', $temp);