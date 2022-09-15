<?php

declare(strict_types=1);

// @codeCoverageIgnoreStart
$lang = [
    'settings' => 'Einstellungen',
    'save_settings' => 'Einstellungen speichern',
    'saving' => 'Speichern',
    'global_settings' => 'Einstellungen',
    'updates' => 'Updates',
    'license' => 'Lizenz',
    'default_host' => 'Standardhost',
    'default_host_explain' => 'URL von dem Bilder geladen werden (z. B. um Bilder via CDN zu laden)',
    'default_max_qty' => 'Vorgabe für Bilder-Höchstzahl',
    'default_max_qty_explain' => 'Vorgabe für neue Ansel-Felder (gilt nicht für bestehende Felder  und kann vom Benutzer abgeändert werden)',
    'default_image_quality' => 'Voreinstellung für Bildqualität',
    'default_image_quality_explain' => 'Vorgabe für neue Ansel-Felder (gilt nicht für bestehende Felder und kann vom Benutzer abgeändert werden)',
    'default_jpg' => 'JPEG standardmäßig erzwingen?',
    'default_jpg_explain' => 'Vorgabe für neue Ansel-Felder (gilt nicht für bestehende Felder und kann vom Benutzer abgeändert werden)',
    'default_retina' => 'Retina-Modus standardmäßig?',
    'default_retina_explain' => 'Vorgabe für neue Ansel-Felder (gilt nicht für bestehende Felder und kann vom Benutzer abgeändert werden)',
    'default_show_title' => 'Titel standardmäßig anzeigen?',
    'default_show_title_explain' => 'Vorgabe für neue Ansel-Felder (gilt nicht für bestehende Felder und kann vom Benutzer abgeändert werden)',
    'default_show_caption' => 'Bildunterschrift standardmäßig anzeigen?',
    'default_show_caption_explain' => 'Vorgabe für neue Ansel-Felder (gilt nicht für bestehende Felder und kann vom Benutzer abgeändert werden)',
    'default_show_cover' => 'Cover standardmäßig anzeigen?',
    'default_show_cover_explain' => 'Vorgabe für neue Ansel-Felder (gilt nicht für bestehende Felder und kann vom Benutzer abgeändert werden)',
    'hide_source_save_instructions' => 'Hinweise zum Upload- und Speicherverzeichnis bei neuen Feldern nicht anzeigen?',
    'hide_source_save_instructions_explain' => 'Bei Nein werden beim Anlegen eines neuen Feldes oberhalb der Optionen Hinweise zur Benutzung des Upload- und Speicherverzeichnisses gegeben. Diese Hinweise können für Benutzer, die damit ausreichend vertraut sind, störend wirken und lassen sich daher deaktivieren.',
    'update' => 'Speichern',
    'updating' => 'Aktualisieren...',
    'settings_updated' => 'Einstellungen aktualisiert',
    'settings_updated_success' => 'Ihre Einstellungen wurden erfolgreich aktualisiert!',
    'ansel_updates' => 'Ansel Updates',
    'ansel_license' => 'Lizenz für Ansel',
    'your_license_key' => 'Ihr Lizenschlüssel',
    'license_updated' => 'Lizenz aktualisiert',
    'license_updated_success' => 'Ihr Lizenschlüssel wurde erfolgreich gespeichert!',
    'ansel_needs_license' => 'Lizenzschlüssel erforderlich',
    'no_license' => 'Danke für den Kauf von Ansel. Sie müssen noch den beim Kauf erhaltenen {{startlink}}Lizenzschlüssel eingeben{{endlink}}.',
    'upload_save_dir_explanation' => 'Hinweise zum Upload- und Speicherverzeichnis',
    'upload_save_dir_hide' => 'Dieser Hinweis kann in den Einstellungen deaktiviert werden',
    'upload_save_dir_explain_upload' => 'Im Upload-Verzeichnis werden die rohen Quelldateien (unbeschnitten und unbearbeitet) gespeichert. Bilder, die in diesem Verzeichnis gespeichert sind oder in diesem gespeichert werden können immer ausgewählt oder einem Feld hinzugefügt werden.',
    'upload_save_dir_explain_save' => 'Im Speicherverzeichnis speichert Ansel die bearbeiteten Bilddateien. Die Dateinamen werden dabei aus der Ansel-Bild-ID und einem Zeitstempel gebildet. Bilder in diesem Verzeichnis sind flüchtig &mdash; sie werden  vom System dynamisch verwaltet, Felder werden aktualisiert und Bilder nach Bedarf hinzugefügt und entfernt. Das Speicherverzeichnis ist nicht für die direkte Verwendung durch den Benutzer gedacht und wird beim Hinzufügen eines Bildes zu einem Ansel-Feld dem Benutzer auch nicht angezeigt.',
    'upload_save_dir_explain_different_sources' => 'Es wird dringend empfohlen für das Upload- und das Speicherverzeichnis nicht dasselbe Verzeichnis zu verwenden. Legen Sie bitte ein separates Verzeichnis an, in dem Ansel die bearbeiteten Bilddateien speichern kann.',
    'upload_directory' => 'Upload-Verzeichnis',
    'upload_directory_explain' => 'Der Upload der Bilddateien erfolgt in dieses Verzeichnis',
    'choose_a_directory' => 'Verzeichnis wählen...',
    'save_directory' => 'Speicherverzeichnis',
    'save_directory_explain' => 'Ansel speichtert die bearbeiteten Bilddateien in diesem Verzeichnis',
    'min_quantity' => 'Mindestanzahl an Bildern',
    'optional' => 'Angabe optional',
    'max_quantity' => 'Höchstanzahl an Bildern',
    'image_quality' => 'Bildqualität',
    'specify_jpeg_image_quality' => 'JPEG-Bildqualität (1 - 100)',
    'force_jpeg' => 'JPEG-Format erzwingen?',
    'force_jpeg_explain' => 'Gespeicherte Bilder zwingend im JPEG-Format speichern',
    'retina_mode' => 'Retina-Modus',
    'retina_mode_explain' => 'Doppelte Abmessungen für feinere Darstellung im Retina-Modus',
    'min_width' => 'Mindestbreite',
    'min_height' => 'Mindesthöhe',
    'max_width' => 'Höchstbreiteh',
    'max_height' => 'Höchsthöhe',
    'crop_ratio' => 'Seitenverhältnis',
    'crop_ratio_explain' => 'Seitenverhältnis (z. B. 1:1, 2:1, 4:3, 16:9) erzwingen. Bitte stellen Sie sicher, dass Ihre Vorgaben für Mindest- und Höchstbreite und -höhe nicht im Widerspruch zum Seitenverhältnis stehen.',
    'display_title_field' => 'Titelfeld anzeigen',
    'display_caption_field' => 'Feld für Bildunterschriften anzeigen',
    'display_cover_field' => 'Feld für Cover anzeigen',
    'customize_title_label' => 'Bezeichnung für Titelfeld anpassen',
    'eg_alt_text' => 'z. B. Alternativer Text',
    'eg_16_9' => 'z. B. 16:9',
    'customize_caption_label' => 'Bezeichnung für Bildunterschrift anpassen',
    'eg_image_description' => 'z. B. Bildbeschreibung',
    'customize_cover_label' => 'Bezeichnung für Cover anpassen',
    'eg_favorite' => 'z. B. Favorit',
    'require_title_field' => 'Titelfeld erzwingen?',
    'require_caption_field' => 'Bildunterschrift erzwingen?',
    'require_cover_field' => 'Cover erzwingen?',
    'min_width_cannot_be_greater_than_max_width' => 'Mindestbreite kann nicht größer als Höchstbreite sein',
    'min_height_cannot_be_greater_than_max_height' => 'Mindesthöhe kann nicht größer als Höchsthöhe sein',
    'specify_crop_width_height' => 'Bitte geben Sie ein Seitenverhältnis (<i>crop ratio</i>) im Format <b>Breite:Höhe</b> an',
    'ee_directories' => 'Installationsverzeichnis für ExpressionEngine',
    'default_require_title' => 'Titel standardmässig erforderlich',
    'default_require_title_explain' => 'Vorgabe für neue Ansel-Felder (gilt nicht für bestehende Felder und kann vom Benutzer abgeändert werden)',
    'default_require_caption' => 'Beschreibung standardmässig erforderlich',
    'default_require_caption_explain' => 'Vorgabe für neue Ansel-Felder (gilt nicht für bestehende Felder und kann vom Benutzer abgeändert werden)',
    'default_title_label' => 'Titellabel standardmässig bearbeitbar',
    'default_title_label_explain' => 'Vorgabe für neue Ansel-Felder (gilt nicht für bestehende Felder und kann vom Benutzer abgeändert werden)',
    'default_caption_label' => 'Beschreibungslabel standardmässig bearbeitbar',
    'default_caption_label_explain' => 'Vorgabe für neue Ansel-Felder (gilt nicht für bestehende Felder und kann vom Benutzer abgeändert werden)',
    'default_require_cover' => 'Cover standardmässig erforderlich',
    'default_require_cover_explain' => 'Vorgabe für neue Ansel-Felder (gilt nicht für bestehende Felder und kann vom Benutzer abgeändert werden)',
    'default_cover_label' => 'Coverlabel standardmässig bearbeitbar',
    'default_cover_label_explain' => 'Vorgabe für neue Ansel-Felder (gilt nicht für bestehende Felder und kann vom Benutzer abgeändert werden)',
    'treasury_directories' => 'Installationsverzeichnis für Treasury',
    'assets_directories' => 'Pfade der Assets',
    'not_negative_number' => 'Darf nicht negativ sein',
    'max_not_less_than_min' => 'Die maximale Menge kann nicht kleiner als die minimale Menge sein',
    'some_data_did_not_validate' => 'Einige Daten konnten nicht validiert werden. Benutzen Sie bitte die Zurück-Taste ihres Browsers',
    'drag_images_to_upload' => 'Bilder zum Hochladen hierher ziehen oder klicken um Bilder auszuwählen',
    'browser_does_not_support_drag_and_drop' => 'Ihr Browser unterstützt kein Upload per drag and drop',
    'please_use_fallback_form' => 'Bitte benutzen Sie das untenstehende Formular um ihre Bilder hochzuladen',
    'file_too_big' => 'Die Datei ist zu gross ({{filesize}}MiB). Maximale Dateigrösse: {{maxFilesize}}MiB.',
    'invalid_file_type' => 'Es können keine Dateien dieses Typs hochgeladen werden',
    'cancel_upload' => 'Upload abbrechen',
    'cancel_upload_confirmation' => 'Wollen Sie diesen Upload wirklich abbrechen?',
    'remove_file' => 'Dokument entfernen',
    'you_cannot_upload_any_more_files' => 'Sie können keine Dokumente mehr hochladen.',
    'min_image_dimensions_not_met' => 'Minimale Bildabmessungen nicht erfüllt',
    'min_image_dimensions_not_met_width_only' => 'Das Bild muss mindestens {{minWidth}}px breit sein',
    'min_image_dimensions_not_met_height_only' => 'Das Bild muss mindestens {{minHeight}}px groß sein',
    'min_image_dimensions_not_met_width_and_height' => 'Das Bild muss mindestens {{minWidth}}px breit und {{minHeight}}px hoch sein',
    'image' => 'Bild',
    'title' => 'Titel',
    'caption' => 'Bildunterschrift',
    'cover' => 'Cover',
    'choose_an_existing_image' => 'Ein bestehendes Bild auswählen',
    'choose_existing_images' => 'Bestehende Bilder auswählen',
    'must_add_1_image' => 'Sie müssen mindestens ein Bild einfügen',
    'must_add_qty_images' => 'Sie müssen mindestens {{qty}} Bilder einfügen',
    'must_add_1_more_image' => 'Sie müssen noch mindestens ein Bild einfügen',
    'must_add_qty_more_images' => 'Sie müssen noch mindestens {{qty}} Bilder einfügen',
    'field_over_limit_1' => 'Dieses Feld ist kann maximal ein Bild enthalten. Zusätzliche Bilder werden nicht angezeigt.',
    'field_over_limit_qty' => 'Dieses Feld ist kann maximal {{qty}} Bilder enthalten. Zusätzliche Bilder werden nicht angezeigt.',
    'file_is_not_an_image' => 'Die ausgewählte Datei ist kein Bild',
    'field_requires_at_least_1_image' => 'Dieses Feld muss mindestens ein Bild enthalten',
    'field_requires_at_least_x_images' => 'Dieses Feld muss mindestens {{amount}} Bilder enthalten',
    'x_field_required_for_each_image' => 'Das Feld {{field}} wird für jedes Bild benötigt',
    'field_requires_cover' => 'Das Feld {{field}} wird für ein Bild benötigt',
    'source_image_missing' => 'Die Quelldatei dieses Bildes ist nicht mehr verfügbar. Möglicherweise wurde sie im Dateimanager gelöscht. Deshalb ist dieses Bild nicht mehr editierbar.',
    'ansel_trial_expired' => 'Die Ansel Probeversion ist abgelaufen',
    'ansel_trial_expired_body' => 'Danke dass Sie Ansel ausprobieren. Die Probezeit ist nun abgelaufen. Ich hoffe Ihnen gefällt Ansel, und Sie {{purchaseLinkStart}}kaufen eine Seitenlizenz{{linkEnd}}. Wenn Sie schon einen Lizenzschlüssel besitzen, {{licenseLinkStart}}geben Sie ihn ein{{linkEnd}}.',
    'ansel_license_invalid' => 'Ansel Lizenz ungültig',
    'ansel_license_invalid_body' => 'Der Lizenzschlüssel den Sie eingegeben haben ist entweder ungültig oder wurde nicht für diese Domain autorisiert.<br><br>Wenn Sie noch keine autorisierte Domain hinzugefügt haben, können Sie dies tun indem Sie sich {{accountLinkStart}}in ihren Account auf BuzzingPixel.com einloggen{{linkEnd}} und die gewünschte Domain hinzufügen.<br><br>Wenn Sie eine Seitenlizenz kaufen möchten, können Sie dies {{purchaseLinkStart}}hier{{linkEnd}} tun. Wenn Sie noch einen anderen Lizenzschlüssel besitzen, können Sie ihn {{licenseLinkStart}}hier eingeben{{linkEnd}}.',
    'license_agreement' => 'Lizenzvereinbarung',
    'prevent_upload_over_max' => 'Uploads verhindern wenn die maximale Anzahl Bilder erreicht wurde',
    'prevent_upload_over_max_explain' => 'Normalerweise erlaubt Ansel das Hochladen überzähliger Bilder, welche ausgegraut erscheinen um zu zeigen, dass sie nicht im Artikel dargestellt werden. Dies ist von Vorteil um Bilder für später vorzubereiten. In seltenen Fällen müssen sie das Hochladen überzähliger Bilder jedoch verhindern.',
    'trial_active_invalid_license_key_body' => 'Ihre Testversion ist noch aktiv, aber der eingegebene Lizenzschlüssel den Sie eingegeben haben ist entweder ungültig oder wurde nicht für diese Domain autorisiert.<br><br>Wenn Sie noch keine autorisierte Domain hinzugefügt haben, können Sie dies tun indem Sie sich {{accountLinkStart}}in ihren Account auf BuzzingPixel.com einloggen{{linkEnd}} und die gewünschte Domain hinzufügen.<br><br>Wenn Sie eine Seitenlizenz kaufen möchten, können Sie dies {{purchaseLinkStart}}hier{{linkEnd}} tun. Wenn Sie noch einen anderen Lizenzschlüssel besitzen, können Sie ihn {{licenseLinkStart}}hier eingeben{{linkEnd}}.',
    'setting_required' => 'Diese Einstellung ist erforderlich',
    'invalid_upload_request' => 'Die Upload-Anfrage war ungültig',
    'image_upload_error' => 'Beim Hochladen Ihres Bildes ist ein Fehler aufgetreten',
    'select_image_from_device' => 'Wählen Sie ein Bild aus, das Sie von Ihrem Gerät hochladen möchten',
    'unusable_image' => 'Die ausgewählte Datei ist kein verwendbares Bild',
    'error_loading_image' => 'Beim Laden des Bildes ist ein Fehler aufgetreten',
    'limited_to_1_image' => 'Dieses Feld wurde auf 1 Bild begrenzt',
    'limited_to_x_images' => 'Dieses Feld wurde auf {{qty}} Bilder begrenzt',
    'select_existing_image' => 'Vorhandenes Bild auswählen',
];
