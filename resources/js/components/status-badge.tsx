import { ApplicationStatus } from '@/types';
import { Badge } from './ui/badge';

const variants: Record<
    ApplicationStatus,
    'default' | 'secondary' | 'destructive' | 'outline'
> = {
    wishlist: 'outline',
    applied: 'secondary',
    interviewing: 'default',
    offer: 'default',
    rejected: 'destructive',
};

export default function StatusBadge({ status }: { status: ApplicationStatus }) {
    return (
        <Badge variant={variants[status]} className="capitalize">
            {status}
        </Badge>
    );
}
