interface EeFileType {
    credit: string;
    description: string;
    file_hw_original: string;
    file_id: number;
    file_name: string;
    file_size: number;
    isImage: boolean;
    isSVG: boolean;
    location: string;
    mime_type: string;
    modified_by_member_id: number;
    modified_date: number;
    path: string;
    site_id: number;
    thumb_path: string;
    title: string;
    upload_date: number;
    upload_location_id: number;
    uploaded_by_member_id: number;
}

export default EeFileType;
