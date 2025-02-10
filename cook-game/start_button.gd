extends Button

func _ready():
	var style = StyleBoxFlat.new()  # Crée un nouveau style
	style.bg_color = Color(0.2, 0.6, 1.0)  # Change la couleur (Bleu)
	add_theme_stylebox_override("hover", style)  # Applique le style
	connect("pressed", _on_start_pressed)


	

func _on_start_pressed():
	print("Le jeu commence !")
	get_tree().change_scene_to_file("res://cuisine.tscn")  # Change vers ta scène de jeu
