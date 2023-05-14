function sendMessageOnEnter(e) {
    if (e.keyCode === 13) {
        e.preventDefault();
        sendMessage();
    }
}

function groupMessagesByDate(messages) {
    const groupedMessages = {};
    messages.forEach(message => {
        const date = formatDate(message.values.created_at.substring(0, 10));
        if (!groupedMessages[date]) {
            groupedMessages[date] = [];
        }
        groupedMessages[date].push(message);
    });
    return groupedMessages;
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}


function loadMessagesOnScreen(response) {
    const messagesContainer = document.querySelector('.messages');
    const chat = document.querySelector('.chat');
    const messageHeight = 160;
    messagesContainer.innerHTML = '';

    const groupedMessages = groupMessagesByDate(response.data);

    for (const [date, messages] of Object.entries(groupedMessages)) {
        const dateHeader = document.createElement('div');
        dateHeader.classList.add('date-header');
        dateHeader.textContent = formatDate(date);
        messagesContainer.appendChild(dateHeader);

        messages.forEach((message, idx) => {
            processMessage(response, messagesContainer, message, idx);
        });
    }
    console.log(chat.scrollTop + messageHeight + ' ' + chat.scrollHeight);
    if(chat.scrollTop + messageHeight + 1000 === chat.scrollHeight){
        chat.scrollTo(0, chat.scrollHeight);
    }
}
