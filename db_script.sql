DROP DATABASE if exists echat;

CREATE DATABASE echat;

CREATE TABLE echat.users (
	username VARCHAR(10) NOT NULL,
	password TEXT NOT NULL,
	image LONGTEXT NOT NULL, /*I'll encode the binary data as text*/
	mime VARCHAR(255) NOT NULL,
	CONSTRAINT user_pk PRIMARY KEY (username)
);

CREATE TABLE echat.messages (
	sender_id VARCHAR(10) NOT NULL,
	receiver_id VARCHAR(10) NOT NULL,
	tmessage TIMESTAMP NOT NULL,
	content TEXT NOT NULL,
	CONSTRAINT user_pk PRIMARY KEY (sender_id, receiver_id, tmessage),
	CONSTRAINT user_fk_sender FOREIGN KEY (sender_id) REFERENCES echat.users (username),
	CONSTRAINT user_fk_receiver FOREIGN KEY (receiver_id) REFERENCES echat.users (username)
);

CREATE USER if not exists 'alumne'@'localhost';
ALTER USER 'alumne'@'localhost' IDENTIFIED BY 'alualualu';
GRANT ALL PRIVILEGES ON echat.* TO 'alumne'@'localhost';

/*
Warning: #1287 Using GRANT statement to modify existing user's properties other than privileges is deprecated and will be removed in future release. Use ALTER USER statement for this operation.

GRANT ALL PRIVILEGES ON echat.* TO 'alumne'@'localhost' IDENTIFIED BY 'alualualu';

*/

/*
CREATE EVENT IF NOT EXISTS `Clean_Every_Hour`
ON SCHEDULE
  EVERY 1 DAY_HOUR
  COMMENT 'Clean up all tables every hour.'
  BEGIN
    DELETE FROM messages;

    DELETE FROM users;

  END;
*/