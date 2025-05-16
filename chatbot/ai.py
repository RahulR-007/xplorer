from datetime import datetime
import random
import time
from fuzzywuzzy import fuzz

# Typing effect
def type_response(response):
    for char in response:
        print(char, end='', flush=True)
        time.sleep(0.01)
    print()

# Utility: Match user input to known patterns with spelling tolerance
def match_input(user_input, keyword_list, threshold=80):
    for keyword in keyword_list:
        if fuzz.partial_ratio(user_input.lower(), keyword.lower()) >= threshold:
            return True
    return False

# Chatbot brain
def get_response(user_input):
    user_input = user_input.lower().strip()

    # 👉 GREETINGS
    if match_input(user_input, ["hello", "hi", "hey", "hii"]):
        return "👋 Hello! How can I assist you with your travel plans today?"

    # 👉 HELP COMMAND
    elif match_input(user_input, ["what can i ask", "help", "options", "what do you do"]):
        return (
            "🧭 You can ask me things like:\n"
            "- Plan a trip\n"
            "- Low / Medium / High budget trip\n"
            "- I'm feeling romantic / adventurous / relaxed\n"
            "- Suggest a destination / place\n"
            "- Auto-plan my weekend\n"
            "- Tell me a joke / quote\n"
            "- What's the time / date\n"
            "- Say bye or exit to quit\n"
        )

    # 👉 PLAN A TRIP
    elif "plan a trip" in user_input:
        destination = None
        budget = None

        # Destination suggestions with details
        suggestions = {
            "manali": {
                "Low": "🏔️ Visit Solang Valley, Jogini Waterfalls, and Hidimba Temple. Stay in budget hostels and enjoy local cafes.",
                "Medium": "🏔️ Explore Solang Valley, Old Manali, and take a snow adventure. Stay in mid-range cottages.",
                "High": "🏔️ Private ski tour, luxury spa at Manali Resorts, and fine dining. Stay at a luxury mountain resort."
            },
            "pondicherry": {
                "Low": "🌊 Visit Rock Beach, Serenity Beach, and Aurobindo Ashram. Stay at budget guesthouses.",
                "Medium": "🌊 Paradise Beach, scuba diving, and café hopping. Stay at boutique hotels.",
                "High": "🌊 Luxury spa retreat, private yacht ride, and gourmet dining. Stay at sea-facing resorts."
            },
            "rishikesh": {
                "Low": "🧘 River rafting, Laxman Jhula, and Ganga Aarti. Stay at cheap lodges.",
                "Medium": "🧘 Yoga classes, camping by the river, and waterfall trekking. Stay at decent hotels.",
                "High": "🧘 Private yoga retreat, wellness spa, and Ganges view luxury stay."
            },
            "goa": {
                "Low": "🌴 Explore Baga Beach, Fort Aguada, and local flea markets. Budget beach huts available.",
                "Medium": "🌴 Dolphin spotting, Anjuna Beach parties, and water sports. Stay at 3-star hotels.",
                "High": "🌴 Private beach villa, yacht party, and fine dining at Taj Exotica."
            },
            "kerala": {
                "Low": "🌿 Visit Alleppey backwaters, Munnar tea gardens, and Kochi Fort. Budget homestays.",
                "Medium": "🌿 Houseboat cruise, spice plantation tour, and Kathakali show. Stay at 4-star resorts.",
                "High": "🌿 Luxury houseboat, Ayurvedic spa, and private tea estate tour."
            },
            "jaipur": {
                "Low": "🏰 Visit Hawa Mahal, Jantar Mantar, and street food tour. Cheap hotels available.",
                "Medium": "🏰 Amber Fort, shopping at Johari Bazaar, and cultural dinner. Stay at heritage hotels.",
                "High": "🏰 Private palace tour, elephant safari, and luxury spa. Stay at Rambagh Palace."
            },
            "europe": {
                "Low": "🌍 Backpack across Prague, Budapest, and Krakow. Stay at hostels.",
                "Medium": "🌍 Eiffel Tower, Venice canals, and Swiss Alps. Stay at boutique hotels.",
                "High": "🌍 Luxury river cruise, private tours in Rome, and Michelin-star dining."
            },
            "maldives": {
                "Low": "🏝️ Public ferry island hopping, local beaches, and snorkeling. Budget guesthouses.",
                "Medium": "🏝️ Water sports, sunset cruise, and reef diving. Stay at water villas.",
                "High": "🏝️ Private island resort, underwater dining, and spa treatments."
            },
            "dubai": {
                "Low": "🏙️ Visit Dubai Mall, Dubai Creek, and public beaches. Stay in budget hotels.",
                "Medium": "🏙️ Desert safari, Dubai Frame, and Marina Cruise. Stay at 4-star hotels.",
                "High": "🏙️ Burj Khalifa VIP tour, private yacht, and stay at Burj Al Arab."
            }
        }

        # Find the destination
        for key in suggestions:
            if key in user_input:
                destination = key
                break

        # Check if budget is mentioned (number or keyword)
        if "low budget" in user_input or "5000" in user_input:
            budget = "Low"
        elif "medium budget" in user_input or "10000" in user_input:
            budget = "Medium"
        elif "high budget" in user_input or "50000" in user_input:
            budget = "High"

        # Respond based on info
        if destination and budget:
            detail = suggestions[destination][budget]
            dest_name = destination.capitalize()
            return f"🧳 Planning a trip to {dest_name} with a {budget} budget? Here are some suggestions:\n{detail}"
        elif destination:
            dest_name = destination.capitalize()
            return f"🧳 Great choice! Please tell me your budget (Low / Medium / High) for {dest_name}."
        else:
            return "🙏 Please mention a destination and budget (e.g., 'Plan a trip to Manali with a budget of 5000')."

    # 👉 MOOD RESPONSES
    elif "feeling" in user_input:
        if "relaxed" in user_input:
            return (
                "😌 That’s wonderful! Here are peaceful destinations:\n"
                "- 🌿 Kerala Backwaters\n"
                "- 🏖️ Pondicherry beaches\n"
                "- 🏔️ Manali hills"
            )
        elif "romantic" in user_input:
            return (
                "💖 Romantic vibes! Here are top spots:\n"
                "- 🌅 Goa sunset beaches\n"
                "- 💦 Udaipur lakeside\n"
                "- 🚣 Kerala houseboats"
            )
        elif "adventurous" in user_input:
            return (
                "🧗‍♂️ Feeling adventurous? Try these:\n"
                "- 🚣 Rishikesh rafting\n"
                "- 🏔️ Manali trekking\n"
                "- 🤿 Andaman scuba diving"
            )
        else:
            return "😊 Let me know your mood: relaxed, romantic, or adventurous!"

    # 👉 JOKES & QUOTES
    elif "joke" in user_input:
        jokes = [
            "😂 Why don’t scientists trust atoms? Because they make up everything!",
            "🤣 I told my suitcase we’re not going on vacation. Now it’s emotional baggage."
        ]
        return random.choice(jokes)

    elif "quote" in user_input:
        quotes = [
            "🌟 'Travel makes one modest. You see what a tiny place you occupy in the world.' – Gustave Flaubert",
            "✈️ 'The world is a book, and those who do not travel read only one page.' – Saint Augustine"
        ]
        return random.choice(quotes)

    # 👉 TIME & DATE
    elif "time" in user_input:
        return f"⏰ Current time: {datetime.now().strftime('%H:%M:%S')}"
    elif "date" in user_input:
        return f"📅 Today is {datetime.now().strftime('%A, %d %B %Y')}"

    # 👉 EXIT
    elif match_input(user_input, ["exit", "quit", "bye", "goodbye"]):
        return "👋 Goodbye! Safe travels with X-plorer!"

    # 👉 DEFAULT RESPONSE
    else:
        return "🙏 Sorry, I didn't get that. Type 'what can I ask you' to see what I can do!"

# Chat loop
if __name__ == "__main__":
    print("🧭 Welcome to the X-plorer Travel Chatbot!")
    print("🗨️ Type 'what can I ask you' to see all options!")
    while True:
        user_input = input("You: ")
        response = get_response(user_input)
        type_response("Chatbot: " + response)
        if any(exit_word in response.lower() for exit_word in ["goodbye", "safe travels"]):
            break
