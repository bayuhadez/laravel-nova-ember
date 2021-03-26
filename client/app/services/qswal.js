import Service from '@ember/service';
import { inject as service } from '@ember/service';

export default class QswalService extends Service {

    @service intl;

    operation = '';

    manual(title, text, type)
    {
        return Swal.fire({
            title: title,
            text: text,
            type: type,
        });
    }

    manualObject(objectParams)
    {
        return Swal.fire(objectParams);
    }

    create()
    {
        this.set('operation', 'create');
        return this;
    }

    edit()
    {
        this.set('operation', 'edit');
        return this;
    }

    delete()
    {
        this.set('operation', 'delete');
        return this;
    }

    // success
    s()
    {
        this.set('type', 'success');
        return this.f();
    }

    // error
    e(error)
    {
        let title;

        if (error.status === "403") {
            title = this.intl.t('message.error.unauthorized');
        }

        this.set('type', 'error');

        return this.f(title);
    }

    // fire
    // if title is not provided, default values will be used
    f(title)
    {
        let operation = this.get('operation');
        let type = this.get('type');
        let text = this.intl.t('message.' + type + '.' + operation);

        if (title === undefined) {
            title = this.intl.t('message.' + type + '.message');
        }

        return Swal.fire({
            title: title,
            text: text,
            type: type,
        });
    }

    confirmDelete(callback)
    {
        return Swal.fire({
            title: this.intl.t('message.confirm'),
            text: this.intl.t('message.cant_revert'),
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: this.intl.t('continue'),
            cancelButtonText: this.intl.t('cancel')
        })
        .then((result) => {
            if (result.value) {
                callback();
            }
        });

    }

    confirmTransition(callback)
    {
        return Swal.fire({
            title: this.intl.t('message.confirm'),
            text: this.intl.t('message.unsaved_changes'),
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: this.intl.t('continue'),
            cancelButtonText: this.intl.t('cancel')
        })
        .then((result) => {
            if (result.value) {
                callback();
            }
        });
    }
}
