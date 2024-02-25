function addFriend() {
    // Get the input fields and the current values
    var newFriendInput = document.getElementById('newFriend');
    var userNameInput = document.getElementById('userName');
    var currentNames = userNameInput.value;
    var friendList = document.getElementById("list-in-modal");
    // Get the new name to be added
    var newFriend = newFriendInput.value;

    // Check if the user entered an empty name
    if (newFriend.trim() === '') {
        return;
    }

    // Add the new friend to the list
    var updatedNames = currentNames === '' ? newFriend : currentNames + ',' + newFriend;

    // Update the input field with the new names
    userNameInput.value = updatedNames;

    var li = document.createElement('li');

    li.textContent = newFriend;

    friendList.appendChild(li);

    // Clear the newFriend input field for the next entry
    newFriendInput.value = '';
}

function showSuccessNotification() {
    var notification = document.getElementById('successNotification');
    notification.style.display = 'block';

    // Hide the notification after 3 seconds (adjust as needed)
    setTimeout(function () {
        notification.style.display = 'none';
    }, 3000);
}

// Optional: Add event listener to trigger addFriend() when Enter key is pressed
document.getElementById('newFriend').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        addFriend();
    }
});