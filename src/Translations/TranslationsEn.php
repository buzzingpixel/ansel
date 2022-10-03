<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Translations;

class TranslationsEn
{
    public const TRANSLATIONS = [
        'settings' => 'Settings',
        'save_settings' => 'Save Settings',
        'saving' => 'Saving',
        'global_settings' => 'Global Settings',
        'updates' => 'Updates',
        'license' => 'License',
        'default_host' => 'Default host',
        'default_host_explain' => 'URL to serve images from (great for serving images from CDN by default)',
        'default_max_qty' => 'Default maximum quantity',
        'default_max_qty_explain' => 'Default value when creating new Ansel fields (does not affect existing fields or prevent setting higher or lower max quantity)',
        'default_image_quality' => 'Default image quality',
        'default_image_quality_explain' => 'Default value when creating new Ansel fields (does not affect existing fields or prevent setting higher or lower quality)',
        'default_jpg' => 'Default force JPG setting',
        'default_jpg_explain' => 'Default value when creating new Ansel fields (does not affect existing fields or prevent choosing a different setting)',
        'default_retina' => 'Default retina mode',
        'default_retina_explain' => 'Default value when creating new Ansel fields (does not affect existing fields or prevent choosing a different setting)',
        'default_show_title' => 'Default display title field',
        'default_show_title_explain' => 'Default value when creating new Ansel fields (does not affect existing fields or prevent choosing a different setting)',
        'default_show_caption' => 'Default display caption field',
        'default_show_caption_explain' => 'Default value when creating new Ansel fields (does not affect existing fields or prevent choosing a different setting)',
        'default_show_cover' => 'Default display cover field',
        'default_show_cover_explain' => 'Default value when creating new Ansel fields (does not affect existing fields or prevent choosing a different setting)',
        'hide_source_save_instructions' => 'Hide the Upload/Save directory instructions when setting up a new field?',
        'hide_source_save_instructions_explain' => 'When set to no, a brief explanation of how to make best use of the Upload/Save directory paradigm will appear above those options when creating a new field. If you already know how this works it can be annoying and you may wish to hide it.',
        'update' => 'Update',
        'updating' => 'Updating',
        'settings_updated' => 'Settings Updated',
        'settings_updated_success' => 'Your settings have been updated successfully!',
        'ansel_updates' => 'Ansel Updates',
        'ansel_license' => 'Ansel License',
        'your_license_key' => 'Your license key',
        'license_updated' => 'License Updated',
        'license_updated_success' => 'Your license key has been saved successfully!',
        'ansel_needs_license' => 'Ansel Needs a License Key',
        'no_license' => 'Thanks so much for purchasing Ansel. All you need to do now is {{startlink}}enter the license key{{endlink}} from your purchase.',
        'upload_save_dir_explanation' => 'Upload/Save directory explanation',
        'upload_save_dir_hide' => 'This message can be hidden in the Ansel settings',
        'upload_save_dir_explain_upload' => 'The upload directory is where raw source images (un-cropped and unmodified) are uploaded and stored. Images in this directory or uploaded to this directory will always be visible when selecting/uploading images to the field.',
        'upload_save_dir_explain_save' => 'The save directory is where Ansel will save and store the captured images. Images are named with the Ansel image ID and timestamp. Images in this directory are transient &mdash; they can come and go as the fields are updated and images are added and removed. The save directory is not meant to be a user-serviceable directory and is not seen by the user when adding/uploading images to the Ansel field.',
        'upload_save_dir_explain_different_sources' => 'It is strongly recommended that you not use the same directory for both Upload and Save. Best practice is to create a separate directory for Ansel to save images to.',
        'upload_directory' => 'Upload Directory',
        'upload_directory_explain' => 'Where to upload source images',
        'choose_a_directory' => 'Choose a directory...',
        'save_directory' => 'Save Directory',
        'save_directory_explain' => 'Where to save captured images',
        'min_quantity' => 'Min Quantity',
        'optional' => 'Optional',
        'max_quantity' => 'Max Quantity',
        'image_quality' => 'Image Quality',
        'specify_jpeg_image_quality' => 'Specify JPEG image quality (1 - 100)',
        'force_jpeg' => 'Force JPEG',
        'force_jpeg_explain' => 'Force the captured image to save as JPEG',
        'retina_mode' => 'Retina Mode',
        'retina_mode_explain' => 'Double dimensions for 2x output',
        'min_width' => 'Min Width',
        'min_height' => 'Min Height',
        'max_width' => 'Max Width',
        'max_height' => 'Max Height',
        'crop_ratio' => 'Crop Ratio',
        'crop_ratio_explain' => 'Constrain image ratio if applicable (1:1, 2:1, 4:3, 16:9). Please note you should make sure your min/max width/height are not in conflict with your crop ratio.',
        'display_title_field' => 'Display title field',
        'display_caption_field' => 'Display caption field',
        'display_cover_field' => 'Display cover field',
        'customize_title_label' => 'Customize title label',
        'eg_alt_text' => 'e.g. Alt Text',
        'eg_16_9' => 'e.g. 16:9',
        'customize_caption_label' => 'Customize caption label',
        'eg_image_description' => 'e.g. Image Description',
        'customize_cover_label' => 'Customize cover label',
        'eg_favorite' => 'e.g. Favorite',
        'require_title_field' => 'Require title field?',
        'require_caption_field' => 'Require caption field?',
        'require_cover_field' => 'Require cover field?',
        'min_width_cannot_be_greater_than_max_width' => 'Min Width cannot be greater than Max Width',
        'min_height_cannot_be_greater_than_max_height' => 'Min Height cannot be greater than Max Height',
        'specify_crop_width_height' => 'Please specify crop ratio in <b>width:height</b> format using only numbers',
        'ee_directories' => 'ExpressionEngine Directories',
        'default_require_title' => 'Default require title',
        'default_require_title_explain' => 'Default value when creating new Ansel fields (does not affect existing fields or prevent choosing a different setting)',
        'default_require_caption' => 'Default require caption',
        'default_require_caption_explain' => 'Default value when creating new Ansel fields (does not affect existing fields or prevent choosing a different setting)',
        'default_title_label' => 'Default customize title label',
        'default_title_label_explain' => 'Default value when creating new Ansel fields (does not affect existing fields or prevent choosing a different setting)',
        'default_caption_label' => 'Default customize caption label',
        'default_caption_label_explain' => 'Default value when creating new Ansel fields (does not affect existing fields or prevent choosing a different setting)',
        'default_require_cover' => 'Default require cover',
        'default_require_cover_explain' => 'Default value when creating new Ansel fields (does not affect existing fields or prevent choosing a different setting)',
        'default_cover_label' => 'Default customize cover label',
        'default_cover_label_explain' => 'Default value when creating new Ansel fields (does not affect existing fields or prevent choosing a different setting)',
        'treasury_directories' => 'Treasury Directories',
        'assets_directories' => 'Assets Directories',
        'not_negative_number' => 'Must not be a negative number',
        'max_not_less_than_min' => 'Max quantity must not be less than min quantity',
        'some_data_did_not_validate' => 'Some data did not validate. Please use the back button on your browser.',
        'drag_images_to_upload' => 'Drag images here to upload',
        'browser_does_not_support_drag_and_drop' => 'Your browser does not support drag and drop file uploads.',
        'please_use_fallback_form' => 'Please use the fallback form below to upload your images',
        'file_too_big' => 'File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.',
        'invalid_file_type' => "You can't upload files of this type",
        'cancel_upload' => 'Cancel upload',
        'cancel_upload_confirmation' => 'Are you sure you want to cancel this upload?',
        'remove_file' => 'Remove file',
        'you_cannot_upload_any_more_files' => "You can't upload any more files.",
        'min_image_dimensions_not_met' => 'Minimum image dimensions not met',
        'min_image_dimensions_not_met_width_only' => 'Image must be at least {{minWidth}}px wide',
        'min_image_dimensions_not_met_height_only' => 'Image must be at least {{minHeight}}px tall',
        'min_image_dimensions_not_met_width_and_height' => 'Image must be at least {{minWidth}}px wide by {{minHeight}}px tall',
        'image' => 'Image',
        'title' => 'Title',
        'caption' => 'Caption',
        'cover' => 'Cover',
        'choose_an_existing_image' => 'Choose an existing image',
        'choose_existing_images' => 'Choose existing images',
        'must_add_1_image' => 'You must add at least 1 image to this field',
        'must_add_qty_images' => 'You must add at least {{qty}} images to this field',
        'must_add_1_more_image' => 'You must add at least 1 more image to this field',
        'must_add_qty_more_images' => 'You must add at least {{qty}} more images to this field',
        'field_over_limit_1' => 'This field is limited to 1 image. All images uploaded beyond that will not be displayed.',
        'field_over_limit_qty' => 'This field is limited to {{qty}} images. All images uploaded beyond that will not be displayed.',
        'file_is_not_an_image' => 'The selected file is not an image',
        'field_requires_at_least_1_image' => 'This field requires at least 1 image',
        'field_requires_at_least_x_images' => 'This field requires at least {{qty}} images',
        'x_field_required_for_each_image' => 'The {{field}} field is required for each image',
        'field_requires_cover' => 'The {{field}} field must be selected on one image',
        'source_image_missing' => 'The source image that was uploaded for this crop has gone missing. It may have been deleted in the file manager. Because of that, this image is no longer editable.',
        'ansel_trial_expired' => 'Ansel Trial Expired',
        'ansel_trial_expired_body' => 'Thank you so much for trying Ansel. The trial period has now expired. I really hope you have enjoyed Ansel and I hope you&rsquo;ll {{purchaseLinkStart}}purchase a site license{{linkEnd}}. If you already have a license key, you can {{licenseLinkStart}}go ahead and enter it{{linkEnd}}.',
        'ansel_license_invalid' => 'Ansel License Invalid',
        'ansel_license_invalid_body' => 'The license key you entered is either invalid, or has not been authorized to run on this domain.<br><br>If you have not added an authorized domain, you may do so by {{accountLinkStart}}logging in to your account page on BuzzingPixel.com{{linkEnd}} and adding the domain you wish to run this license on.<br><br>If you need to purchase a site license, you can do so by {{purchaseLinkStart}}clicking here{{linkEnd}}. If you have another license key, go ahead and enter it by {{licenseLinkStart}}clicking here{{linkEnd}}.',
        'license_agreement' => 'License Agreement',
        'prevent_upload_over_max' => 'Prevent file uploads when max quantity reached',
        'prevent_upload_over_max_explain' => 'Normally, Ansel will allow uploads beyond max quantity gray them out to indicate they will not be displayed. This is great for preparing images for later. But rarely you need to keep those images from uploading at all.',
        'trial_active_invalid_license_key_body' => 'Your trial is still active, but the license key you entered is either invalid, or has not been authorized to run on this domain.<br><br>If you have not added an authorized domain, you may do so by {{accountLinkStart}}logging in to your account page on BuzzingPixel.com{{linkEnd}} and adding the domain you wish to run this license on.<br><br>If you need to purchase a site license, you can do so by {{purchaseLinkStart}}clicking here{{linkEnd}}. If you have another license key, go ahead and enter it by {{licenseLinkStart}}clicking here{{linkEnd}}.',
        'setting_required' => 'This setting is required',
        'invalid_upload_request' => 'The upload request was invalid',
        'image_upload_error' => 'There was an error uploading your image',
        'select_image_from_device' => 'Select an image to upload from your device',
        'unusable_image' => 'The selected file is not a usable image',
        'error_loading_image' => 'There was an error loading the image',
        'limited_to_1_image' => 'This field is limited to 1 image',
        'limited_to_x_images' => 'This field is limited to {{qty}} images',
        'select_existing_image' => 'Select existing image',
        'or' => 'or',
        'remove_image' => 'Remove Image',
        'edit_fields' => 'Edit Fields',
        'edit_focal_point' => 'Edit Focal Point',
        'edit_image' => 'Edit Image',
        'place_focal_point' => 'Click on the image to place focal point. Press red button (or escape) to cancel. Press green button (or enter) to accept changes.',
        'edit_image_crop' => 'Edit image crop. Press red button (or escape) to cancel. Press green button (or enter) to accept changes.',
        'must_not_add_more_than_1_image' => 'You must not add more than 1 image to this field',
        'must_not_add_more_than_x_images' => 'You must not add more than {{qty}} images to this field',
        'custom_field_x_required' => 'The field "{{fieldLabel}}" is required',
    ];
}
