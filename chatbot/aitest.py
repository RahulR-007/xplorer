from datasets import load_dataset
from sentence_transformers import SentenceTransformer, util
import torch

# Load a public dataset (e.g., Cornell Movie Dialogs)
dataset = load_dataset("cornell_movie_dialogs")
conversations = dataset['train']['dialog']

# Flatten conversations into pairs (user, bot) for training
pairs = []
for conv in conversations:
    turns = conv.split('\n')
    for i in range(len(turns)-1):
        pairs.append((turns[i], turns[i+1]))

# Separate questions and answers
questions = [q for q, a in pairs]
answers = [a for q, a in pairs]

# Load embedding model
model = SentenceTransformer('all-MiniLM-L6-v2')

# Encode questions
question_embeddings = model.encode(questions, convert_to_tensor=True)

def get_response(user_input):
    user_emb = model.encode(user_input, convert_to_tensor=True)
    scores = util.cos_sim(user_emb, question_embeddings)[0]
    best_idx = torch.argmax(scores).item()
    return answers[best_idx]

print("Chatbot ready! Type 'exit' to quit.")
while True:
    user_input = input("You: ")
    if user_input.lower() == 'exit':
        break
    print("Bot:", get_response(user_input))
