let mediaRecorder;

    let audioChunks = [];

    document.getElementById('startBtn').onclick = async () => {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });

        mediaRecorder = new MediaRecorder(stream);

        mediaRecorder.ondataavailable = event => {
            audioChunks.push(event.data);
        };

        mediaRecorder.onstop = async () => {
            let currentTime = () =>{
                const now = new Date();

                const options = {
                month: 'long',   // full month name
                day: 'numeric',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false    // use 24-hour format; remove for AM/PM
                };

                const formatted = now.toLocaleString(undefined, options);
                return formatted;
            }

            const audioBlob = new Blob(audioChunks, { type: 'audio/ogg' });
            const formData = new FormData();
            formData.append('file', audioBlob);

            const response = await fetch('transcribes.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            let newNote = document.createElement("div");
            let noteTitle = document.createElement("h3");
            noteTitle.textContent = "Note: " + currentTime();
            let noteBody = document.createElement("p");
            noteBody.textContent = data.transcript;
            newNote.appendChild(noteTitle);
            newNote.appendChild(noteBody);
            document.getElementById('transcript').appendChild(newNote);
            audioChunks = [];
        };

        mediaRecorder.start();
    };

    document.getElementById('stopBtn').onclick = () => {
        mediaRecorder.stop();
    };