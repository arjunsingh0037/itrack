<?php

// Written at Louisiana State University

$capabilities = array(
    'block/quickmailsms:myaddinstance' => array(
        'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
            'frontpage' => CAP_PREVENT,
            'user' => CAP_PREVENT
        )
    ),

    'block/quickmailsms:addinstance' => array(
        'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW,
            'accountmanager' => CAP_ALLOW,
            'partner' => CAP_ALLOW,
            'trainingpartner' => CAP_ALLOW,
            'professor' => CAP_ALLOW,
        ),
        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),

    'block/quickmailsms:cansend' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'accountmanager' => CAP_ALLOW,
            'partner' => CAP_ALLOW,
            'trainingpartner' => CAP_ALLOW,
            'professor' => CAP_ALLOW,
        )
    ),

    'block/quickmailsms:allowalternate' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW
        )
    ),

    'block/quickmailsms:canconfig' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW
        )
    ),

    'block/quickmailsms:canimpersonate' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
        )
    ),

    'block/quickmailsms:candelete' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
        )
    ),
);
