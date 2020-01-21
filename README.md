# echat

To do list after 1st commit:
X - Characters like 'á' are stored but when the messages are loaded they are wrong.
- Change to the new way to work with MySQL in PHP (close connections).
- Load only the new messages (not all the messages).
- Re-factorize JS/jQuery code (global variables coupled to functions).
- Use sockets.io (to replace execute function every 2 seconds)

Done in commit:
- Solved: Characters like 'á' are stored but when the messages are loaded they are wrong.
- User list and messages are only loaded when there are new ones (but all of them are loaded, not only new ones).
- Return key now wotking to send messages.
- User selected from the user list has a different style (class).
