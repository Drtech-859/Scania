import sys
import os
import tensorflow as tf
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing import image
import numpy as np


def load_and_preprocess_image(image_path):
    # Load the image using Keras and preprocess it
    img = image.load_img(image_path, target_size=(224, 224))
    img = image.img_to_array(img)
    
    # Convert the image to three channels if it's grayscale
    if img.shape[-1] == 1:
        img = np.repeat(img, 3, axis=-1)
    
    img = img / 255.0  # Rescale pixel values between 0 and 1
    img = np.expand_dims(img, axis=0)
    return img



def predict(image_path):
    # Load the pre-trained model
    model = load_model('scania.h5')

    # Preprocess the image
    img = load_and_preprocess_image(image_path)

    # Make the prediction
    result = model.predict(img)

    # Get the predicted class label
    class_label = np.argmax(result)

    # Check the prediction probability
    prediction_probability = np.max(result)

    # Set the prediction label based on the probability threshold
    if prediction_probability > 0.5:
        prediction_label = "PNEUMONIA"
    else:
        prediction_label = "NORMAL"

    # Return the predicted class label as a string
    return prediction_label

if __name__ == '__main__':
    # Retrieve the file path passed as a command-line argument
    file_path = sys.argv[1]
    # Call the predict function
    prediction = predict(file_path)
    # Print the prediction result
    print(prediction)
