# Autostenographer
An implementation of Azure AI tools to create an automatic stenography tool.

## Tools
The site uses access to GPT-4o and GPT-4o-transcribe to create a simple tool to record notes for the user and allow the user to either download a complete transcript of what they've been saying or alternatively get a summary of the various notes and their overarching themes/topics.

## Site model!
![Site diagram](/img/Autostenographer_diagram.png)
The site uses a simple front page file with two main php backend helper files which facilitate the communication to the Azure GPT endpoints.

## AI usage
>GPT-4o - Simple prompt design needed to give AI context for how to handle the summarization of the notes it would be given. 

>GPT-4o-transcribe - Use of standard model for speech to text was all that was needed.

[Pesentation slides](https://docs.google.com/presentation/d/13wBDnyquQsmke3Eg7WHBjDLYzlbjAbPZ1kzD3vXfPJk/edit?usp=sharing)
