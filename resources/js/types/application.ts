export type ApplicationStatus =
    | 'wishlist'
    | 'applied'
    | 'interviewing'
    | 'offer'
    | 'rejected';

export type Interview = {
    id: number;
    application_id: number;
    scheduled_at: string;
    type: string;
    notes: string | null;
};

export type Application = {
    id: number;
    company: string;
    role: string;
    status: ApplicationStatus;
    applied_at: string | null;
    url: string | null;
    notes: string | null;
    interview_count?: number;
    interviews?: Interview[];
};

export type Paginated<T> = {
    data: T[];
    current_page: number;
    last_page: number;
    total: number;
    prev_page_url: string | null;
    next_page_url: string | null;
};
