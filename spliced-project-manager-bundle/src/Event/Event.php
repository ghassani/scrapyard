<?php

namespace Spliced\Bundle\ProjectManagerBundle\Event;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

class Event extends BaseEvent
{
    const PROJECT_ATTRIBUTE_SAVE   = 'spliced_pms.project_attribute_save';
    const PROJECT_ATTRIBUTE_UPDATE = 'spliced_pms.project_attribute_update';
    const PROJECT_ATTRIBUTE_DELETE = 'spliced_pms.project_attribute_delete';

    const PROJECT_STAFF_SAVE   = 'spliced_pms.project_staff_save';
    const PROJECT_STAFF_UPDATE = 'spliced_pms.project_staff_update';
    const PROJECT_STAFF_DELETE = 'spliced_pms.project_staff_delete';

    const PROJECT_NOTE_SAVE   = 'spliced_pms.project_note_save';
    const PROJECT_NOTE_UPDATE = 'spliced_pms.project_note_update';
    const PROJECT_NOTE_DELETE = 'spliced_pms.project_note_delete';

    const PROJECT_TIME_ENTRY_SAVE   = 'spliced_pms.project_time_entry_save';
    const PROJECT_TIME_ENTRY_UPDATE = 'spliced_pms.project_time_entry_update';
    const PROJECT_TIME_ENTRY_DELETE = 'spliced_pms.project_time_entry_delete';

    const PROJECT_INVOICE_SAVE   = 'spliced_pms.project_invoice_save';
    const PROJECT_INVOICE_UPDATE = 'spliced_pms.project_invoice_update';
    const PROJECT_INVOICE_DELETE = 'spliced_pms.project_invoice_delete';

    const PROJECT_FILE_SAVE   = 'spliced_pms.project_file_save';
    const PROJECT_FILE_UPDATE = 'spliced_pms.project_file_update';
    const PROJECT_FILE_UPLOAD = 'spliced_pms.project_file_upload';
    const PROJECT_FILE_DELETE = 'spliced_pms.project_file_delete';

    const PROJECT_MEDIA_SAVE   = 'spliced_pms.project_media_save';
    const PROJECT_MEDIA_UPDATE = 'spliced_pms.project_media_update';
    const PROJECT_MEDIA_UPLOAD = 'spliced_pms.project_media_upload';
    const PROJECT_MEDIA_DELETE = 'spliced_pms.project_media_delete';

    const PROJECT_TAG_SAVE   = 'spliced_pms.project_tag_save';
    const PROJECT_TAG_DELETE = 'spliced_pms.project_tag_delete';

    const TAG_SAVE   = 'spliced_pms.tag_save';
    const TAG_UPDATE = 'spliced_pms.tag_update';
    const TAG_DELETE = 'spliced_pms.tag_delete';

    const STAFF_SAVE   = 'spliced_pms.staff_save';
    const STAFF_UPDATE = 'spliced_pms.staff_update';
    const STAFF_DELETE = 'spliced_pms.staff_delete';

    const CLIENT_SAVE   = 'spliced_pms.client_save';
    const CLIENT_UPDATE = 'spliced_pms.client_update';
    const CLIENT_DELETE = 'spliced_pms.client_delete';
}
