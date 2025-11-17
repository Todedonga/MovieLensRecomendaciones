import sys
import json
import numpy as np
import pandas as pd
import os

# --- FORZAR UTF-8 ---
import io
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

DATA_DIR = "ml-1m"
movies = pd.read_csv(os.path.join(DATA_DIR, "movies_processed.csv"))

model = np.load("rbm_model.npy", allow_pickle=True).item()
W = model["W"]
vb = model["vb"]
hb = model["hb"]

# Recibir el archivo JSON desde PHP
json_file = sys.argv[1]

with open(json_file, "r", encoding="utf-8") as f:
    user_ratings = json.load(f)

v = np.zeros(len(movies))

for movie_id, rating in user_ratings.items():
    movie_id = int(movie_id)
    idx = movies[movies["MovieID"] == movie_id].iloc[0]["List Index"]
    v[int(idx)] = rating / 5

h = 1 / (1 + np.exp(-(np.dot(v, W) + hb)))
rec = 1 / (1 + np.exp(-(np.dot(h, W.T) + vb)))

movies["Score"] = rec

watched = list(map(int, user_ratings.keys()))
recs = movies[~movies["MovieID"].isin(watched)]
recs = recs.sort_values("Score", ascending=False).head(3)

def explanation(movie_row):
    genres = movie_row["Genres"].split("|")

    for mid, rating in user_ratings.items():
        mid = int(mid)
        g2 = movies[movies["MovieID"] == mid].iloc[0]["Genres"].split("|")

        if len(set(genres).intersection(set(g2))) > 0:
            original_title = movies[movies['MovieID'] == mid].iloc[0]['Title']
            return f"Porque te gustó '{original_title}', te recomendamos '{movie_row['Title']}' ya que comparten géneros similares."

    return f"Usuarios similares disfrutaron '{movie_row['Title']}'."

# Generar salida limpia
output = []

for _, row in recs.iterrows():
    output.append({
        "movieID": int(row["MovieID"]),
        "title": str(row["Title"]),
        "genres": str(row["Genres"]),
        "explanation": explanation(row)
    })

# Imprimir JSON en UTF-8
print(json.dumps(output, ensure_ascii=False))