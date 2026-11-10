document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('cakeModal');
    if (!modal) return;

    modal.addEventListener('show.bs.modal', (event) => {
        const trigger = event.relatedTarget;
        if (!trigger) return;

        const editing = trigger.dataset.formMode === 'edit';
        modal.querySelector('#cakeModalTitle').textContent = editing ? 'Edit cake' : 'Add a cake';
        modal.querySelector('#cakeSubmit').textContent = editing ? 'Save changes' : 'Add cake';
        modal.querySelector('#cakeId').value = editing ? trigger.dataset.id : '';
        modal.querySelector('#cakeName').value = editing ? trigger.dataset.name : '';
        modal.querySelector('#cakeDescription').value = editing ? trigger.dataset.description : '';
        modal.querySelector('#cakePrice').value = editing ? trigger.dataset.price : '';
        modal.querySelector('#cakeSeason').value = editing ? trigger.dataset.season : 'Birthday';
        modal.querySelector('#cakeImage').value = '';
        modal.querySelector('#imageHint').textContent = editing
            ? 'Optional — leave blank to keep the current image'
            : 'JPG, PNG, GIF or WEBP · max 5 MB';
    });
});
