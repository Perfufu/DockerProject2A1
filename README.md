# ProjetDocker — Setup Guide

## 1. Installer les prérequis

### Docker

```bash
sudo apt install ca-certificates curl
sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/debian/gpg -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc

sudo tee /etc/apt/sources.list.d/docker.sources <<EOF
Types: deb
URIs: https://download.docker.com/linux/debian
Suites: $(. /etc/os-release && echo "$VERSION_CODENAME")
Components: stable
Signed-By: /etc/apt/keyrings/docker.asc
EOF

sudo apt update
sudo apt install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```

### Git

```bash
sudo apt install -y git
```

### Vérification

```bash
docker --version
docker compose version
git --version
```

---

## 2. Cloner le projet

Sur GitHub, aller sur [https://github.com/perfufu/ProjetDocker](https://github.com/perfufu/ProjetDocker) et cliquer sur **Fork** en haut à droite.

Puis cloner son fork :

```bash
git clone https://github.com/VOTRE_PSEUDO/ProjetDocker.git
cd ProjetDocker
```

---

## 3. Créer le fichier secret

```bash
mkdir -p docker/secrets
printf 'root' > docker/secrets/db_password.txt
```

> Ce fichier contient le mot de passe de la base de données. Il n'est pas versionné (.gitignore).

---

## 4. Lancer l'application

### Option A — Build local

Pour rebuilder les images depuis les sources :

```bash
docker build -f docker/Dockerfile.frontend -t perfufu/projetdocker-frontend:latest ./front
docker build -f docker/Dockerfile.backend  -t perfufu/projetdocker-backend:latest  ./back
docker build -f docker/Dockerfile.bdd      -t perfufu/projetdocker-db:latest       ./database

cd docker
docker compose up -d
```

### Option B — Depuis Docker Hub

Les images de référence sont disponibles sur Docker Hub :

```bash
docker login
cd docker
docker compose up -d
```

Docker téléchargera automatiquement les images `perfufu/projetdocker-frontend`, `perfufu/projetdocker-backend` et `perfufu/projetdocker-db`.

---

## 5. Vérification

```bash
docker compose ps          # vérifier que les 3 conteneurs sont up
docker compose logs db     # vérifier que MySQL a bien démarré
docker compose logs back   # vérifier que le seed a bien tourné
```

Les 3 conteneurs doivent être en statut `running` :

| Conteneur | Rôle   | Port       |
|-----------|--------|------------|
| front     | Nginx  | 8081       |
| back      | PHP    | 8080       |
| db        | MySQL  | non exposé |

---

## 6. Accès

**Frontend** → [http://localhost:8081](http://localhost:8081)

Comptes disponibles :

| Username | Password  |
|----------|-----------|
| admin    | admin123  |
| alice    | alice123  |
| bob      | bob123    |

---

## 7. Arrêt

```bash
docker compose down      # arrête les conteneurs
docker compose down -v   # arrête et supprime les données (volume db)
```

---

### Version minimaliste

```bash
sudo apt install -y ca-certificates curl git && \
sudo install -m 0755 -d /etc/apt/keyrings && \
sudo curl -fsSL https://download.docker.com/linux/debian/gpg -o /etc/apt/keyrings/docker.asc && \
sudo chmod a+r /etc/apt/keyrings/docker.asc && \
sudo tee /etc/apt/sources.list.d/docker.sources <<EOF
Types: deb
URIs: https://download.docker.com/linux/debian
Suites: $(. /etc/os-release && echo "$VERSION_CODENAME")
Components: stable
Signed-By: /etc/apt/keyrings/docker.asc
EOF
sudo apt update && \
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin && \
git clone https://github.com/Perfufu/ProjetDocker.git && \
cd ProjetDocker && \
mkdir -p docker/secrets && \
printf 'root' > docker/secrets/db_password.txt && \
cd docker && \
docker compose up -d
```