document.addEventListener('DOMContentLoaded', () => {
    const createClassBtn = document.getElementById('create-class-btn');
    const joinClassBtn = document.getElementById('join-class-btn');
    const viewClassesBtn = document.getElementById('view-classes-btn');
    const createClassModal = document.getElementById('create-class-modal');
    const joinClassModal = document.getElementById('join-class-modal');
    const closeCreateClassModal = document.getElementById('close-create-class-modal');
    const closeJoinClassModal = document.getElementById('close-join-class-modal');
    const createClassForm = document.getElementById('create-class-form');
    const joinClassForm = document.getElementById('join-class-form');

    createClassBtn.addEventListener('click', () => {
        createClassModal.style.display = 'block';
    });

    joinClassBtn.addEventListener('click', () => {
        joinClassModal.style.display = 'block';
    });

    closeCreateClassModal.addEventListener('click', () => {
        createClassModal.style.display = 'none';
    });

    closeJoinClassModal.addEventListener('click', () => {
        joinClassModal.style.display = 'none';
    });

    createClassForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const formData = new FormData(createClassForm);

        fetch('dashboard.php', {
            method: 'POST',
            body: new URLSearchParams([...formData, ['action', 'crear_clase']])
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                createClassModal.style.display = 'none';
                location.reload(); 
            }
        })
        .catch(error => console.error('Error:', error));
    });

    joinClassForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const formData = new FormData(joinClassForm);

        fetch('dashboard.php', {
            method: 'POST',
            body: new URLSearchParams([...formData, ['action', 'unirse_clase']])
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                joinClassModal.style.display = 'none';
                window.location.href = `gestionar_clase.php?clase_id=${data.clase_id}`;
            }
        })
        .catch(error => console.error('Error:', error));
    });
});