export default function UnsavedDataNotification({ show = false }: { show: boolean }) {
    if (!show) return;

    return <div className="mt-5 text-sm">You have unsaved changes</div>;
}
