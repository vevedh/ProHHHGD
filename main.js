'use strict';

const electron = require('electron');
const path = require('path');
//const pjson = require('package.json');
//const Store = require('electron-store');
//const tcpp = require('tcp-ping');
//const resolve = require('resolve');
//const settings = new Store();
const { app, BrowserWindow, Menu, Tray, nativeImage, globalShortcut, dialog, ipcMain, ipcRenderer } = require('electron');
const iconPath = path.join(__dirname, 'www/assets/icon/icon512.png');


//const internetAvailable = require("internet-available");

let hasInternet = false;









// Keep a global reference of the window object, if you don't, the window will
// be closed automatically when the javascript object is GCed.
let mainWindow = null;
let willQuitApp = false;
let trayIcon = null;
let traymenu =null;
let glpiWindow = null;

// Quit when all windows are closed.
app.on('window-all-closed', () => {
  if (process.platform != 'darwin')
    app.quit();
});

// This method will be called when Electron has done everything
// initialization and ready for creating browser windows.
app.on('ready', function () {

  var app_context_menu = [
    {
      label: 'Quitter',
      accelerator: 'CmdOrCtrl+Q',
      click: () => {
        willQuitApp = true;
        if (process.platform != 'darwin')
          app.quit();
      }
    }

  ];

  var application_menu = [
    {
      label: 'menu1',
      submenu: [
        {
          label: 'Undo',
          accelerator: 'CmdOrCtrl+Z',
          role: 'undo'
        },
        {
          label: 'Open',
          accelerator: 'CmdOrCtrl+O',
          click: () => {
            electron.dialog.showOpenDialog({ properties: ['openFile', 'openDirectory', 'multiSelections'] });
          }
        },
        {
          label: 'submenu1',
          submenu: [
            {
              label: 'item1',
              accelerator: 'CmdOrCtrl+A',
              click: () => {
                mainWindow.openDevTools();
              }
            },
            {
              label: 'item2',
              accelerator: 'CmdOrCtrl+B',
              click: () => {
                mainWindow.closeDevTools();
              }
            }
          ]
        }
      ]
    }
  ];
  if (process.platform == 'darwin') {
    const name = app.getName();

    application_menu.unshift({
      label: name,
      submenu: [
        {
          label: 'About ' + name,
          role: 'about'
        },
        {
          type: 'separator'
        },
        {
          label: 'Services',
          role: 'services',
          submenu: []
        },
        {
          type: 'separator'
        },
        {
          label: 'Hide ' + name,
          accelerator: 'Command+H',
          role: 'hide'
        },
        {
          label: 'Hide Others',
          accelerator: 'Command+Shift+H',
          role: 'hideothers'
        },
        {
          label: 'Show All',
          role: 'unhide'
        },
        {
          type: 'separator'
        },
        {
          label: 'Quit',
          accelerator: 'Command+Q',
          click: () => { app.quit(); }
        },
      ]
    });
  }


  // defini une icon en barre de tache
  trayIcon = new Tray(nativeImage.createFromPath(iconPath));
  trayIcon.setToolTip('ProHHHGD: Gestion du SI à HO HIO HEN Grande Distribution');
  traymenu = Menu.buildFromTemplate(app_context_menu);
  trayIcon.setContextMenu(traymenu);

  // Create the browser window.
  mainWindow = new BrowserWindow({
    autoHideMenuBar: true,
    webPreferences: {
      nodeIntegration: true,
      devTools: true
    },
    resizable: true,
    width: 400,
    height: 740
  });

  mainWindow.setMenu(null);
  // mainWindow.webContents.openDevTools();

  // and load the index.html of the app.
  mainWindow.loadURL('file://' + __dirname + '/www/index.html');




  // defini un menu pour l'application
  //menu = Menu.buildFromTemplate(application_menu);
  //Menu.setApplicationMenu(menu);

  // Emitted when the window is closed.

  trayIcon.on('click', (e) => {
    mainWindow.show();
  });

  mainWindow.on('close', (e) => {
    //willQuitApp = true;
    if (willQuitApp) {
      /* the user tried to quit the app */
      mainWindow = null;
      //server.close();
    } else {
      /* the user only tried to close the window */
      e.preventDefault();
      mainWindow.hide();
    }
  });

})