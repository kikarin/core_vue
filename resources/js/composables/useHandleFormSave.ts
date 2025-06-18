import { router } from '@inertiajs/vue3'
import { useToast } from '@/components/ui/toast/useToast'

type SaveOptions = {
    url: string
    redirectUrl?: string
    mode: 'create' | 'edit'
    id?: number | string
    successMessage?: string
    errorMessage?: string
    onSuccess?: () => void
}

export function useHandleFormSave() {
    const { toast } = useToast()

    const save = (data: Record<string, any>, options: SaveOptions) => {
        const {
            url,
            redirectUrl = url,
            mode,
            id,
            successMessage = 'Data berhasil disimpan',
            errorMessage = 'Gagal menyimpan data',
            onSuccess,
        } = options

        const request = mode === 'create'
            ? router.post(url, data, {
                onSuccess: () => {
                    toast({ title: successMessage, variant: 'success' })
                    onSuccess ? onSuccess() : router.visit(redirectUrl)
                },
                onError: (errors) => {
                    console.error('Create Error:', errors)
                    toast({ title: errorMessage, variant: 'destructive' })
                },
            })
            : router.put(`${url}/${id}`, data, {
                onSuccess: () => {
                    toast({ title: successMessage, variant: 'success' })
                    onSuccess ? onSuccess() : router.visit(redirectUrl)
                },
                onError: (errors) => {
                    for (const key in errors) {
                        console.error(`Error on ${key}:`, errors[key])
                    }
                    toast({ title: errorMessage, variant: 'destructive' })
                },
            })

        return request
    }

    return { save }
}
