document.addEventListener('DOMContentLoaded', () => {
    const createExamBtn = document.getElementById('create-exam-btn');
    const createClassMaterialBtn = document.getElementById('create-class-material-btn');
    const createExamModal = document.getElementById('create-exam-modal');
    const createClassMaterialModal = document.getElementById('create-class-material-modal');
    const closeCreateExamModal = document.getElementById('close-create-exam-modal');
    const closeCreateClassMaterialModal = document.getElementById('close-create-class-material-modal');
    const createExamForm = document.getElementById('create-exam-form');
    const createClassMaterialForm = document.getElementById('create-class-material-form');

    createExamBtn.addEventListener('click', () => {
        createExamModal.style.display = 'block';
    });

    createClassMaterialBtn.addEventListener('click', () => {
        createClassMaterialModal.style.display = 'block';
    });

    closeCreateExamModal.addEventListener('click', () => {
        createExamModal.style.display = 'none';
    });

    closeCreateClassMaterialModal.addEventListener('click', () => {
        createClassMaterialModal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === createExamModal || event.target === createClassMaterialModal) {
            createExamModal.style.display = 'none';
            createClassMaterialModal.style.display = 'none';
        }
    });

    createExamForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const formData = new FormData(createExamForm);
        formData.append('action', 'create_exam');

        fetch('gestionar_clase.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Examen creado con éxito');
                createExamModal.style.display = 'none';
                createExamForm.reset();
            } else {
                alert('Error al crear el examen: ' + data.error);
            }
        });
    });

    createClassMaterialForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const formData = new FormData(createClassMaterialForm);
        formData.append('action', 'create_material');

        fetch('gestionar_clase.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Material de clase creado con éxito');
                createClassMaterialModal.style.display = 'none';
                createClassMaterialForm.reset();
            } else {
                alert('Error al crear el material de clase: ' + data.error);
            }
        });
    });
});
