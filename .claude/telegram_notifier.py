#!/usr/bin/env python3
"""
Simple Telegram Notifier - Siap Pakai!
Ganti BOT_TOKEN dan CHAT_ID dengan data Anda, lalu langsung bisa digunakan.
"""

import urllib.request
import urllib.parse
import json
import time
import sys
import os
from datetime import datetime

# Fix encoding untuk Windows
if sys.platform == 'win32':
    sys.stdout.reconfigure(encoding='utf-8')

# ========================================
# KONFIGURASI - GANTI DENGAN DATA ANDA!
# ========================================
BOT_TOKEN = "8433236389:AAEjlAjea7LfcD66-zXP-oxHh1d34kheDAA"  # Dari @BotFather
CHAT_ID = "8048437668"      # Dari @userinfobot

def send_telegram_message(message, silent=False):
    """
    Fungsi utama untuk kirim pesan ke Telegram
    
    Args:
        message (str): Pesan yang akan dikirim
        silent (bool): True untuk notifikasi silent
    """
    if BOT_TOKEN == "YOUR_BOT_TOKEN_HERE" or CHAT_ID == "YOUR_CHAT_ID_HERE":
        print("❌ Harap isi BOT_TOKEN dan CHAT_ID terlebih dahulu!")
        print("\n📋 Cara setup:")
        print("1. Chat @BotFather → /newbot → copy Bot Token")
        print("2. Chat @userinfobot → copy Chat ID")
        print("3. Paste kedua data ke script ini")
        return False
    
    url = f"https://api.telegram.org/bot{BOT_TOKEN}/sendMessage"
    
    data = {
        "chat_id": CHAT_ID,
        "text": message,
        "parse_mode": "HTML",
        "disable_notification": silent
    }
    
    try:
        req = urllib.request.Request(
            url, 
            data=json.dumps(data).encode('utf-8'),
            headers={'Content-Type': 'application/json'}
        )
        
        with urllib.request.urlopen(req) as response:
            result = json.loads(response.read().decode('utf-8'))
            
            if result.get("ok"):
                print("✅ Pesan terkirim ke Telegram!")
                return True
            else:
                print(f"❌ Gagal kirim: {result.get('description', 'Error tidak diketahui')}")
                return False
                
    except Exception as e:
        print(f"❌ Error: {e}")
        return False

def notify_info(title, message):
    """Kirim notifikasi info"""
    formatted_msg = f"ℹ️ <b>{title}</b>\n\n{message}\n\n🕒 <i>{datetime.now().strftime('%Y-%m-%d %H:%M:%S')}</i>"
    return send_telegram_message(formatted_msg)

def notify_success(title, message):
    """Kirim notifikasi sukses"""
    formatted_msg = f"✅ <b>{title}</b>\n\n{message}\n\n🕒 <i>{datetime.now().strftime('%Y-%m-%d %H:%M:%S')}</i>"
    return send_telegram_message(formatted_msg)

def notify_warning(title, message):
    """Kirim notifikasi warning"""
    formatted_msg = f"⚠️ <b>{title}</b>\n\n{message}\n\n🕒 <i>{datetime.now().strftime('%Y-%m-%d %H:%M:%S')}</i>"
    return send_telegram_message(formatted_msg)

def notify_error(title, message):
    """Kirim notifikasi error"""
    formatted_msg = f"❌ <b>{title}</b>\n\n{message}\n\n🕒 <i>{datetime.now().strftime('%Y-%m-%d %H:%M:%S')}</i>"
    return send_telegram_message(formatted_msg)

def notify_progress(task_name, current, total, details=""):
    """Kirim update progress dengan progress bar"""
    percentage = round((current / total) * 100, 1)
    filled = int(percentage // 5)
    progress_bar = "█" * filled + "░" * (20 - filled)
    
    message = f"📊 <b>{task_name}</b>\n\n"
    message += f"Progress: {current}/{total} ({percentage}%)\n"
    message += f"<code>[{progress_bar}]</code>\n"
    
    if details:
        message += f"\n{details}"
    
    message += f"\n\n🕒 <i>{datetime.now().strftime('%H:%M:%S')}</i>"
    
    return send_telegram_message(message, silent=True)  # Silent untuk progress

def test_connection():
    """Test apakah bot sudah setup dengan benar"""
    if BOT_TOKEN == "YOUR_BOT_TOKEN_HERE" or CHAT_ID == "YOUR_CHAT_ID_HERE":
        print("⚠️ Bot belum dikonfigurasi!")
        return False
    
    url = f"https://api.telegram.org/bot{BOT_TOKEN}/getMe"
    
    try:
        with urllib.request.urlopen(url) as response:
            result = json.loads(response.read().decode('utf-8'))
            
            if result.get("ok"):
                bot_info = result.get("result", {})
                bot_name = bot_info.get("first_name", "Unknown")
                print(f"✅ Bot '{bot_name}' siap digunakan!")
                
                # Kirim test message
                test_msg = f"🤖 <b>Bot Test Berhasil!</b>\n\nBot <b>{bot_name}</b> sudah siap digunakan untuk notifikasi Python Anda."
                return send_telegram_message(test_msg)
            else:
                print(f"❌ Bot token tidak valid: {result.get('description', '')}")
                return False
                
    except Exception as e:
        print(f"❌ Koneksi gagal: {e}")
        return False

def handle_hook_event(event_type):
    """Handle Claude Code hook events"""
    hook_messages = {
        "UserPromptSubmit": {
            "icon": "💬",
            "title": "Claude Code - User Prompt",
            "message": "User telah mengirim prompt baru ke Claude Code"
        },
        "Stop": {
            "icon": "🛑",
            "title": "Claude Code - Stopped",
            "message": "Eksekusi Claude Code dihentikan"
        },
        "SubagentStop": {
            "icon": "⏹️",
            "title": "Claude Code - Subagent Stopped",
            "message": "Subagent Claude Code dihentikan"
        },
        "Notification": {
            "icon": "🔔",
            "title": "Claude Code - Notification",
            "message": "Ada notifikasi baru dari Claude Code"
        }
    }
    
    if event_type in hook_messages:
        hook_info = hook_messages[event_type]
        formatted_msg = f"{hook_info['icon']} <b>{hook_info['title']}</b>\n\n{hook_info['message']}\n\n🕒 <i>{datetime.now().strftime('%Y-%m-%d %H:%M:%S')}</i>"
        return send_telegram_message(formatted_msg)
    else:
        # Default message untuk event tidak dikenal
        formatted_msg = f"📌 <b>Claude Code Event</b>\n\nEvent: {event_type}\n\n🕒 <i>{datetime.now().strftime('%Y-%m-%d %H:%M:%S')}</i>"
        return send_telegram_message(formatted_msg)

def main():
    """Demo penggunaan notifier atau handle hook event"""
    # Cek apakah dipanggil dari hook
    if len(sys.argv) > 1:
        event_type = sys.argv[1]
        handle_hook_event(event_type)
        return
    
    # Mode demo jika dipanggil langsung
    print("📱 Telegram Python Notifier")
    print("=" * 40)
    
    # Test koneksi
    if not test_connection():
        print("\n📋 Setup Instructions:")
        print("1. Chat dengan @BotFather di Telegram")
        print("2. Ketik /newbot dan ikuti instruksi")
        print("3. Copy Bot Token ke script ini")
        print("4. Chat dengan @userinfobot untuk dapatkan Chat ID")
        print("5. Copy Chat ID ke script ini")
        print("\n🔗 Quick Links:")
        print("   • https://t.me/BotFather")
        print("   • https://t.me/userinfobot")
        return
    
    print("\n🚀 Mengirim contoh notifikasi...\n")
    
    # 1. Notifikasi info
    notify_info(
        "Script Dimulai", 
        "Python script telah dimulai dan siap bekerja!"
    )
    
    time.sleep(2)
    
    # 2. Simulasi progress
    for i in range(0, 101, 20):
        notify_progress(
            "Data Processing",
            i, 100,
            f"Memproses batch {i//20 + 1}/6..."
        )
        time.sleep(1)
    
    # 3. Warning
    notify_warning(
        "Memory Usage", 
        "Penggunaan memory mencapai 80%. Monitoring diperlukan."
    )
    
    time.sleep(2)
    
    # 4. Success
    notify_success(
        "Task Completed", 
        "Semua proses telah selesai dengan sukses! 🎉"
    )
    
    print("✅ Demo selesai! Cek Telegram Anda untuk melihat notifikasi.")

# ==============================================
# FUNGSI-FUNGSI HELPER UNTUK DIGUNAKAN DI SCRIPT LAIN
# ==============================================

def quick_notify(message):
    """Fungsi cepat untuk notifikasi sederhana"""
    return send_telegram_message(f"📢 <b>Python Alert</b>\n\n{message}")

def notify_when_done(func):
    """Decorator untuk notifikasi otomatis saat fungsi selesai"""
    def wrapper(*args, **kwargs):
        func_name = func.__name__
        
        # Notifikasi mulai
        notify_info(f"🚀 {func_name} Started", f"Fungsi {func_name} telah dimulai.")
        
        start_time = time.time()
        try:
            result = func(*args, **kwargs)
            end_time = time.time()
            duration = round(end_time - start_time, 2)
            
            # Notifikasi selesai
            notify_success(
                f"✅ {func_name} Completed",
                f"Fungsi selesai dalam {duration} detik."
            )
            return result
            
        except Exception as e:
            end_time = time.time()
            duration = round(end_time - start_time, 2)
            
            # Notifikasi error
            notify_error(
                f"❌ {func_name} Failed",
                f"Error setelah {duration} detik:\n\n<code>{str(e)}</code>"
            )
            raise
            
    return wrapper

# Contoh penggunaan decorator
@notify_when_done
def long_running_task():
    """Contoh fungsi yang memakan waktu"""
    print("Melakukan task yang lama...")
    time.sleep(5)  # Simulasi proses lama
    print("Task selesai!")
    return "Success"

if __name__ == "__main__":
    main()
    
    # Uncomment untuk test decorator:
    # print("\n" + "="*50)
    # print("Testing decorator...")
    # long_running_task()