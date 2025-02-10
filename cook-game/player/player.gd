extends Area2D

@export var speed: float = 200  # Vitesse du personnage$

func _physics_process(delta: float) -> void:
	var direction = Vector2.ZERO  # Direction du mouvement

	# Vérifie quelles touches sont pressées
	if Input.is_action_pressed("ui_right"):
		direction.x += 1
		$AnimatedSprite2D.play("droite")
	elif Input.is_action_pressed("ui_left"):
		direction.x -= 1
		$AnimatedSprite2D.play("walk_left")
	elif Input.is_action_pressed("ui_up"):
		direction.y -= 1
		$AnimatedSprite2D.play("avancer")
	elif Input.is_action_pressed("ui_down"):
		direction.y += 1
		$AnimatedSprite2D.play("dos")
	else:
		$AnimatedSprite2D.stop()  # Arrête l'animation quand aucun mouvement

	direction = direction.normalized()  # Évite une vitesse trop rapide en diagonale
	velocity = direction * speed
	move_and_slide()  # Applique le mouvement
