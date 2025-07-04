#!/bin/bash

# יצירת מבנה התיקיות
mkdir -p backend/websocket
mkdir -p database
mkdir -p admin
mkdir -p portal
mkdir -p chatbot
mkdir -p uploads
mkdir -p logs
mkdir -p docker/apache
mkdir -p scripts
mkdir -p error-pages

# יצירת קבצי הבסיס
touch backend/config.php
touch backend/database.php
touch backend/websocket/package.json
touch backend/websocket/chat-server.js
touch api/index.php
touch database/schema.sql

echo "מבנה הקבצים נוצר!"
