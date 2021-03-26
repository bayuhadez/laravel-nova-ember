import Component from '@ember/component';
import { computed } from '@ember/object';

export default Component.extend({
    // use this component on hbs file
    // {{metronic/inputs/m-fileupload label="file upload" model=service.name}}
    isError: false,

    showError: computed('isError', function() {
        return this.isError;
    }),

    actions: {
        onFileChanged()
        {
            let file = this.$().find('input')[0].files[0];
            if (file.size < (this.fileSizeLimit ? 2097152 : (file.size+1))) {
                this.set('isError', false);
                let reader = new FileReader();
    
                reader.onload = ((e) => {
                    let toUpload = e.srcElement.result;
                    return this.onFileChanged(toUpload);
                });
    
                reader.readAsDataURL(file);
            } else {
                this.set('isError', true);
                this.$().find('input')[0].value = null;
            }
        },
    }
});
