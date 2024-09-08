document.addEventListener('DOMContentLoaded', () => {
    const questionsContainer = document.getElementById('questionsContainer');
    const createExamButton = document.getElementById('createExam');
    const createExamAIButton = document.getElementById('createExamAI');
    const addMoreQuestionsButton = document.getElementById('addMoreQuestions');
    const examNameInput = document.getElementById('examNameInput');
    const classIdInput = document.querySelector('input[name="classId"]');
    const maxQuestions = 30;
    let questionCount = 0;

    createExamButton.addEventListener('click', () => {
        questionsContainer.innerHTML = `
            <h3>Nombre del Examen</h3>
            <input type="text" id="examNameInput" name="examName" placeholder="Nombre del examen" required>
            <div id="questionsList">
                <!-- Aquí se agregarán las preguntas -->
            </div>
            <button type="button" id="saveExam" class="button">Guardar Examen</button>
        `;
        questionsContainer.style.display = 'block';
        createExamButton.style.display = 'none'; // Ocultar el botón de crear examen
        createExamAIButton.style.display = 'none'; // Ocultar el botón de crear examen por IA
        addMoreQuestionsButton.style.display = 'block'; // Mostrar el botón de agregar más preguntas
        
        // Inicializar la primera pregunta
        addQuestion();
    });

    addMoreQuestionsButton.addEventListener('click', () => {
        if (questionCount < maxQuestions) {
            addQuestion();
        } else {
            alert('Se ha alcanzado el límite máximo de 30 preguntas.');
        }
    });

    function addQuestion() {
        questionCount++;
        const questionDiv = document.createElement('div');
        questionDiv.classList.add('question');
        questionDiv.innerHTML = `
            <h3>Pregunta ${questionCount}</h3>
            <input type="text" name="questionTitle${questionCount}" placeholder="Título de la pregunta" required>
            <h4>Opciones</h4>
            <input type="text" name="option1_${questionCount}" placeholder="Opción 1" required>
            <input type="text" name="option2_${questionCount}" placeholder="Opción 2" required>
            <input type="text" name="option3_${questionCount}" placeholder="Opción 3">
        `;
        document.getElementById('questionsList').appendChild(questionDiv);
    }

    document.getElementById('saveExam').addEventListener('click', () => {
        const examName = examNameInput.value.trim();
        if (examName === '') {
            alert('Por favor, introduce el nombre del examen.');
            return;
        }

        const questions = [];
        const questionDivs = document.querySelectorAll('#questionsList .question');

        questionDivs.forEach((questionDiv, index) => {
            const questionTitle = questionDiv.querySelector(`input[name="questionTitle${index + 1}"]`).value.trim();
            const options = [
                { text: questionDiv.querySelector(`input[name="option1_${index + 1}"]`).value.trim(), isCorrect: false },
                { text: questionDiv.querySelector(`input[name="option2_${index + 1}"]`).value.trim(), isCorrect: false },
                { text: questionDiv.querySelector(`input[name="option3_${index + 1}"]`).value.trim(), isCorrect: false }
            ];

            questions.push({ question: questionTitle, options });
        });

        // Enviar los datos al servidor
        fetch('save_exam.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                examName: examName,
                classId: classIdInput.value,
                questions: JSON.stringify(questions),
            }),
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    createExamAIButton.addEventListener('click', () => {
        const examName = examNameInput.value.trim();
        if (examName === '') {
            alert('Por favor, introduce el nombre del examen.');
            return;
        }
        // Aquí se puede agregar la lógica para crear un examen usando IA
        alert('Crear examen por IA con nombre: ' + examName);
    });
});
