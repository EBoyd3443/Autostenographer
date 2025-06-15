let mediaRecorder;
    let summaryRest = false;

    let audioChunks = [];

    let chatLog = "";

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
            chatLog += "Note: " + currentTime() + "\n";
            chatLog += data.transcript + "\n";
            newNote.appendChild(noteTitle);
            newNote.appendChild(noteBody);
            document.getElementById('transcript').appendChild(newNote);
            audioChunks = [];
        };

        mediaRecorder.start();
    };

    document.getElementById('getSummary').onclick = async () => {
        if(!summaryRest) {
            summaryRest = true;

            const response = await fetch('summary.php', {
                method: 'POST',
                body: JSON.stringify({ notes: chatLog })
            })
            console.log(response);
            const summary = await response.json();
            const blob = new Blob([summary['transcript']], { type: 'text/plain' });
            const fileURL = URL.createObjectURL(blob);
            const downloadLink = document.createElement('a');
            downloadLink.href = fileURL;
            downloadLink.download = 'Note_summary.txt';

            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
            URL.revokeObjectURL(fileURL);

            summaryRest = false;
        }
    }

    document.getElementById('getFullNotes').onclick = async () => {
        const blob = new Blob(chatLog, { type: 'text/plain' });
        const fileURL = URL.createObjectURL(blob);
        const downloadLink = document.createElement('a');
        downloadLink.href = fileURL;
        downloadLink.download = 'Note_summary.txt';

        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
        URL.revokeObjectURL(fileURL);
    }

    document.getElementById('stopBtn').onclick = () => {
        mediaRecorder.stop();
    };