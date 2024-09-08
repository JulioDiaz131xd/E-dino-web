document.addEventListener('DOMContentLoaded', () => {
    // Elementos para crear exámenes y materiales de clase
    const createExamBtn = document.getElementById('create-exam-btn');
    const createClassMaterialBtn = document.getElementById('create-class-material-btn');
    const createExamModal = document.getElementById('create-exam-modal');
    const createClassMaterialModal = document.getElementById('create-class-material-modal');
    const closeCreateExamModal = document.getElementById('close-create-exam-modal');
    const closeCreateClassMaterialModal = document.getElementById('close-create-class-material-modal');
    const createExamForm = document.getElementById('create-exam-form');
    const createClassMaterialForm = document.getElementById('create-class-material-form');

    // Elementos para ver miembros
    const viewMembersBtn = document.getElementById('view-members-btn');
    const membersModal = document.getElementById('members-modal');
    const closeMembersModal = document.getElementById('close-members-modal');
    const membersList = document.getElementById('members-list');

    // Abrir modal de exámenes
    createExamBtn.addEventListener('click', () => {
        createExamModal.style.display = 'block';
    });

    // Abrir modal de materiales de clase
    createClassMaterialBtn.addEventListener('click', () => {
        createClassMaterialModal.style.display = 'block';
    });

    // Cerrar modal de exámenes
    closeCreateExamModal.addEventListener('click', () => {
        createExamModal.style.display = 'none';
    });

    // Cerrar modal de materiales de clase
    closeCreateClassMaterialModal.addEventListener('click', () => {
        createClassMaterialModal.style.display = 'none';
    });

    // Cerrar modales si se hace clic fuera de ellos
    window.addEventListener('click', (event) => {
        if (event.target === createExamModal || event.target === createClassMaterialModal) {
            createExamModal.style.display = 'none';
            createClassMaterialModal.style.display = 'none';
        }
    });

    // Crear un examen
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

    // Crear material de clase
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

    // Abrir modal de miembros
    viewMembersBtn.addEventListener('click', () => {
        membersModal.style.display = 'block';
        
        // Obtener miembros de la clase
        fetch('gestionar_clase.php', {
            method: 'POST',
            body: new URLSearchParams({
                'action': 'ver_miembros',
                'clase_id': new URLSearchParams(window.location.search).get('clase_id')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                membersList.innerHTML = data.miembros.map(miembro => `
                    <li>${miembro.nombre} ${miembro.apellidos} ${miembro.es_creador ? '(Creador)' : ''}</li>
                `).join('');
            } else {
                alert('Error al cargar los miembros: ' + data.error);
            }
        });
    });

    // Cerrar modal de miembros
    closeMembersModal.addEventListener('click', () => {
        membersModal.style.display = 'none';
    });

    // Cerrar modal de miembros si se hace clic fuera de él
    window.addEventListener('click', (event) => {
        if (event.target === membersModal) {
            membersModal.style.display = 'none';
        }
    });
});
