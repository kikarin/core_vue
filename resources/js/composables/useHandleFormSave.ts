import { useToast } from '@/components/ui/toast/useToast';
import { router } from '@inertiajs/vue3';

type SaveOptions = {
    url: string;
    redirectUrl?: string;
    mode: 'create' | 'edit';
    id?: number | string;
    successMessage?: string;
    errorMessage?: string;
    onSuccess?: () => void;
};

export function useHandleFormSave() {
    const { toast } = useToast();

    const handleError = (errors: Record<string, any>, fallbackMessage: string) => {
        if (!errors || Object.keys(errors).length === 0) {
            toast({ title: fallbackMessage, variant: 'destructive' });
            return;
        }

        Object.entries(errors).forEach(([field, message]) => {
            toast({
                title: `${field}: ${message}`,
                variant: 'destructive',
            });
        });
    };

    const save = (data: Record<string, any>, options: SaveOptions) => {
        const {
            url,
            redirectUrl = url,
            mode,
            id,
            successMessage = 'Data berhasil disimpan',
            errorMessage = 'Gagal menyimpan data',
            onSuccess,
        } = options;

        const request =
            mode === 'create'
                ? router.post(url, data, {
                      onSuccess: () => {
                          toast({ title: successMessage, variant: 'success' });
                          onSuccess ? onSuccess() : router.visit(redirectUrl);
                      },
                      onError: (errors) => handleError(errors, errorMessage),
                  })
                : router.put(`${url}/${id}`, data, {
                      onSuccess: () => {
                          toast({ title: successMessage, variant: 'success' });
                          onSuccess ? onSuccess() : router.visit(redirectUrl);
                      },
                      onError: (errors) => handleError(errors, errorMessage),
                  });

        return request;
    };

    return { save };
}
