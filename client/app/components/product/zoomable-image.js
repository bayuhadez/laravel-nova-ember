import Component from '@ember/component';

export default Component.extend({
    actions: {
        zoomImage(product)
        {
            Swal.fire({
                html: "<img style='max-width:100%;' src="+product.get('imageUrl')+">",
                width: "1000px",
            });
        }
    }
});
