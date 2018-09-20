<?php

function xmldb_block_quickmailsms_upgrade($oldversion) {
    global $DB;

    $result = true;

    $dbman = $DB->get_manager();

    // 1.9 to 2.0 upgrade
    if ($oldversion < 2011021812) {
        // Changing type of field attachment on table block_quickmailsms_log to text
        $table = new xmldb_table('block_quickmailsms_log');
        $field = new xmldb_field('attachment', XMLDB_TYPE_TEXT, 'small', null, XMLDB_NOTNULL, null, null, 'message');

        // Launch change of type for field attachment
        $dbman->change_field_type($table, $field);

        // Rename field timesent on table block_quickmailsms_log to time
        $table = new xmldb_table('block_quickmailsms_log');
        $field = new xmldb_field('timesent', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'format');

        // Conditionally launch rename field timesent
        if ($dbman->field_exists($table, $field)) {
            $dbman->rename_field($table, $field, 'time');
        }

        // Define table block_qmsms_signatures to be created
        $table = new xmldb_table('block_qmsms_signatures');

        // Adding fields to table block_qmsms_signatures
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '11', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0');
        $table->add_field('title', XMLDB_TYPE_CHAR, '125', null, null, null, null);
        $table->add_field('signature', XMLDB_TYPE_TEXT, 'medium', null, null, null, null);
        $table->add_field('default_flag', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '1');

        // Adding keys to table block_qmsms_signatures
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_qmsms_signatures
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table block_quickmailsms_drafts to be created
        $table = new xmldb_table('block_quickmailsms_drafts');

        // Adding fields to table block_quickmailsms_drafts
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '11', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0');
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '11', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0');
        $table->add_field('mailto', XMLDB_TYPE_TEXT, 'medium', null, null, null, null);
        $table->add_field('subject', XMLDB_TYPE_TEXT, 'small', null, null, null, null);
        $table->add_field('message', XMLDB_TYPE_TEXT, 'medium', null, null, null, null);
        $table->add_field('attachment', XMLDB_TYPE_TEXT, 'small', null, XMLDB_NOTNULL, null, null);
        $table->add_field('format', XMLDB_TYPE_INTEGER, '3', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '1');
        $table->add_field('time', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, null);

        // Adding keys to table block_quickmailsms_drafts
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_quickmailsms_drafts
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table block_quickmailsms_config to be created
        $table = new xmldb_table('block_quickmailsms_config');

        // Adding fields to table block_quickmailsms_config
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('coursesid', XMLDB_TYPE_INTEGER, '11', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '25', null, XMLDB_NOTNULL, null, null);
        $table->add_field('value', XMLDB_TYPE_CHAR, '125', null, null, null, null);

        // Adding keys to table block_quickmailsms_config
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_quickmailsms_config
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // quickmailsms savepoint reached
        upgrade_block_savepoint($result, 2011021812, 'quickmailsms');
    }

    if ($oldversion < 2012021014) {
        $table = new xmldb_table('block_quickmailsms_alternate');

        $field = new xmldb_field('id');
        $field->set_attributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED,
            XMLDB_NOTNULL, true, null, null);

        $table->addField($field);

        $field = new xmldb_field('courseid');
        $field->set_attributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED,
            XMLDB_NOTNULL, false, null, 'id');

        $table->addField($field);

        $field = new xmldb_field('address');
        $field->set_attributes(XMLDB_TYPE_CHAR, '100', null,
            XMLDB_NOTNULL, false, null, 'courseid');

        $table->addField($field);

        $field = new xmldb_field('valid');
        $field->set_attributes(XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED,
            XMLDB_NOTNULL, false, '0', 'address');

        $table->addField($field);

        $key = new xmldb_key('PRIMARY');
        $key->set_attributes(XMLDB_KEY_PRIMARY, array('id'));

        $table->addKey($key);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        foreach (array('log', 'drafts') as $table) {
            // Define field alternateid to be added to block_quickmailsms_log
            $table = new xmldb_table('block_quickmailsms_' . $table);
            $field = new xmldb_field('alternateid', XMLDB_TYPE_INTEGER, '10',
                XMLDB_UNSIGNED, null, null, null, 'userid');

            // Conditionally launch add field alternateid
            if (!$dbman->field_exists($table, $field)) {
                $dbman->add_field($table, $field);
            }
        }

        // quickmailsms savepoint reached
        upgrade_block_savepoint($result, 2012021014, 'quickmailsms');
    }

    if ($oldversion < 2012061112) {
        // Restructure database references to the new filearea locations
        foreach (array('log', 'drafts') as $type) {
            $params = array(
                'component' => 'block_quickmailsms_' . $type,
                'filearea' => 'attachment'
            );

            $attachments = $DB->get_records('files', $params);

            foreach ($attachments as $attachment) {
                $attachment->filearea = 'attachment_' . $type;
                $attachment->component = 'block_quickmailsms';

                $result = $result && $DB->update_record('files', $attachment);
            }
        }

        upgrade_block_savepoint($result, 2012061112, 'quickmailsms');
    }
    if ($oldversion < 2012061112) {
    	migrate_quickmailsms_20();
    }
    
    if($oldversion < 2014042914){

         // Define field status to be dropped from block_quickmailsms_log.
        $table = new xmldb_table('block_quickmailsms_log');
        $field = new xmldb_field('status');

        // Conditionally launch drop field status.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Define field status to be added to block_quickmailsms_log.
        $table = new xmldb_table('block_quickmailsms_log');
	$field = new xmldb_field('failuserids', XMLDB_TYPE_TEXT, null, null, null, null, null, 'time');
        $field2 = new xmldb_field('additional_emails', XMLDB_TYPE_TEXT, null, null, null, null, null, 'failuserids');
        // Conditionally launch add field status.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
	if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }
        
         // Define field additional_emails to be added to block_quickmailsms_drafts.
        $table = new xmldb_table('block_quickmailsms_drafts');
        $field = new xmldb_field('additional_emails', XMLDB_TYPE_TEXT, null, null, null, null, null, 'time');

        // Conditionally launch add field additional_emails.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        
        
        // QuickmailSMS savepoint reached.
        upgrade_block_savepoint(true, 2014042914, 'quickmailsms');
    }
    return $result;
}
