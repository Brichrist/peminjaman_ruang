#!/usr/bin/env python3
"""Sleek modern notification with gradient colors"""

import sys
import tkinter as tk
from tkinter import font as tkfont
from datetime import datetime
from pathlib import Path
import threading
import subprocess

def show_sleek_popup(hook_type):
    """Show sleek modern popup notification"""
    
    # Create window
    root = tk.Tk()
    root.overrideredirect(True)  # Remove window decorations
    root.attributes('-topmost', True)  # Always on top
    
    # Modern color schemes
    configs = {
        "UserPromptSubmit": {
            "bg": "#7C3AED",      # Violet
            "accent": "#A78BFA",   # Light violet
            "icon": "💜",
            "title": "Message Sent"
        },
        "Stop": {
            "bg": "#10B981",      # Emerald
            "accent": "#6EE7B7",   # Light emerald
            "icon": "✨",
            "title": "Complete"
        },
        "SubagentStop": {
            "bg": "#3B82F6",      # Blue
            "accent": "#93C5FD",   # Light blue
            "icon": "🚀",
            "title": "Agent Done"
        },
        "Notification": {
            "bg": "#F59E0B",      # Amber
            "accent": "#FCD34D",   # Light amber
            "icon": "🔔",
            "title": "Notification"
        },
        "default": {
            "bg": "#06B6D4",      # Cyan
            "accent": "#67E8F9",   # Light cyan
            "icon": "💫",
            "title": "Alert"
        }
    }
    
    config = configs.get(hook_type, configs["default"])
    
    # Set window size
    width = 280
    height = 80
    root.geometry(f"{width}x{height}")
    
    # Main container with rounded corner effect
    main_frame = tk.Frame(root, bg=config["bg"])
    main_frame.pack(fill="both", expand=True)
    
    # Content container
    content = tk.Frame(main_frame, bg=config["bg"])
    content.pack(fill="both", expand=True, padx=15, pady=12)
    
    # Top row - Icon and Title
    top_row = tk.Frame(content, bg=config["bg"])
    top_row.pack(fill="x")
    
    # Icon
    icon_label = tk.Label(
        top_row,
        text=config["icon"],
        font=("Segoe UI Emoji", 18),
        bg=config["bg"],
        fg="white"
    )
    icon_label.pack(side="left", padx=(0, 10))
    
    # Title and hook type container
    text_container = tk.Frame(top_row, bg=config["bg"])
    text_container.pack(side="left", fill="x", expand=True)
    
    # Title
    title_font = tkfont.Font(family="Segoe UI", size=11, weight="bold")
    title_label = tk.Label(
        text_container,
        text=config["title"],
        font=title_font,
        bg=config["bg"],
        fg="white",
        anchor="w"
    )
    title_label.pack(anchor="w")
    
    # Hook type
    type_font = tkfont.Font(family="Segoe UI", size=9)
    type_label = tk.Label(
        text_container,
        text=hook_type,
        font=type_font,
        bg=config["bg"],
        fg=config["accent"],
        anchor="w"
    )
    type_label.pack(anchor="w")
    
    # Bottom row - Time and progress
    bottom_row = tk.Frame(content, bg=config["bg"])
    bottom_row.pack(fill="x", pady=(8, 0))
    
    # Time
    timestamp = datetime.now().strftime("%H:%M:%S")
    time_font = tkfont.Font(family="Segoe UI", size=8)
    time_label = tk.Label(
        bottom_row,
        text=timestamp,
        font=time_font,
        bg=config["bg"],
        fg=config["accent"]
    )
    time_label.pack(side="left")
    
    # Progress bar background
    progress_bg = tk.Frame(bottom_row, bg=config["accent"], height=3)
    progress_bg.pack(side="bottom", fill="x", pady=(5, 0))
    
    # Progress bar fill
    progress = tk.Frame(progress_bg, bg="white", height=3)
    progress.place(x=0, y=0, relwidth=0)
    
    # Animate progress
    close_delay = 5000 if hook_type == "Stop" else 3000
    steps = 100
    step_delay = close_delay // steps
    
    def animate_progress(step=0):
        if step <= steps and root.winfo_exists():
            try:
                progress.place(relwidth=step/steps)
                root.after(step_delay, lambda: animate_progress(step + 1))
            except:
                pass
        elif root.winfo_exists():
            try:
                root.destroy()
            except:
                pass
    
    # Position window
    root.update_idletasks()
    screen_width = root.winfo_screenwidth()
    screen_height = root.winfo_screenheight()
    
    x = screen_width - width - 30
    y = screen_height - height - 80
    
    root.geometry(f"{width}x{height}+{x}+{y}")
    
    # Fade in effect
    root.attributes('-alpha', 0)
    
    def fade_in(alpha=0):
        if alpha < 0.95 and root.winfo_exists():
            alpha += 0.05
            try:
                root.attributes('-alpha', alpha)
                root.after(10, lambda: fade_in(alpha))
            except:
                pass
    
    fade_in()
    animate_progress()
    
    # Play sound
    def play_sound():
        try:
            import ctypes
            
            if hook_type == "Stop":
                mp3_file = Path(__file__).parent / "notif.mp3"
            else:
                mp3_file = Path(__file__).parent / "notif2.mp3"
            
            mp3_path = str(mp3_file.absolute())
            
            winmm = ctypes.windll.winmm
            winmm.mciSendStringW("close notifaudio", None, 0, None)
            
            open_cmd = f'open "{mp3_path}" type mpegvideo alias notifaudio'
            winmm.mciSendStringW(open_cmd, None, 0, None)
            
            volume_cmd = 'setaudio notifaudio volume to 1000'
            winmm.mciSendStringW(volume_cmd, None, 0, None)
            
            play_cmd = 'play notifaudio'
            winmm.mciSendStringW(play_cmd, None, 0, None)
            
        except:
            try:
                import winsound
                winsound.MessageBeep(winsound.MB_OK)
            except:
                pass
    
    sound_thread = threading.Thread(target=play_sound)
    sound_thread.daemon = True
    sound_thread.start()
    
    # Run window
    root.mainloop()

def log_event(hook_type):
    """Log notification event"""
    try:
        log_file = Path(__file__).parent / "notification.log"
        timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        with open(log_file, "a") as f:
            f.write(f"[{timestamp}] {hook_type}: Sleek notification shown\n")
    except:
        pass

def send_telegram_notification(hook_type):
    """Send telegram notification"""
    try:
        # Call telegram notifier in background
        telegram_script = Path(__file__).parent / "telegram_notifier.py"
        subprocess.Popen(
            ["py", str(telegram_script), hook_type],
            stdout=subprocess.DEVNULL,
            stderr=subprocess.DEVNULL,
            creationflags=subprocess.CREATE_NO_WINDOW if sys.platform == 'win32' else 0
        )
    except:
        pass

if __name__ == "__main__":
    hook_type = sys.argv[1] if len(sys.argv) > 1 else "default"
    
    # Print to console
    timestamp = datetime.now().strftime("%H:%M:%S")
    print(f"\033[94m[{timestamp}] [NOTIFICATION] {hook_type}\033[0m")
    
    # Log event
    log_event(hook_type)
    
    # Send telegram notification
    send_telegram_notification(hook_type)
    
    # Show sleek popup
    try:
        show_sleek_popup(hook_type)
    except Exception as e:
        print(f"Visual notification failed: {e}")
    
    sys.exit(0)