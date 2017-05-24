from __future__ import division
from scipy.misc import *
from scipy.io import *
from numpy import *
import sys
import shutil
import subprocess

home_dir = '/home/ubuntu/deeplab'

def show_object(imgname):
    classname = 'clock'
    crop_imagefile(imgname)
    shutil.move(home_dir + '/examples/images/cropped/' + imgname + '.jpg', home_dir + '/examples/images/cropped/img_object.jpg')
    subprocess.call(home_dir + '/run_' + classname + '.sh', shell=True)
    shutil.move(home_dir + '/crf/' + classname + '/img_object_blob_0.mat',  home_dir + '/crf/' + classname + '/' + imgname + '_blob_0.mat')
    shutil.move(home_dir + '/examples/images/cropped/img_object.jpg', home_dir + '/examples/images/cropped/' + imgname + '.jpg')
    var = loadmat(home_dir + '/crf/' + classname + '/' + imgname + '_blob_0.mat')
    scoremap = var['data']
    scoremap_size = scoremap.shape
    size_x = scoremap_size[0]
    size_y = scoremap_size[1]
    no_of_labels = scoremap_size[2] - 1
    label_score = zeros((no_of_labels, 1))
    for i in range(no_of_labels):
        label_score[i] = sum(sum(scoremap[:,:,i+1], axis=0), axis=0)
    max_label_index = argsort(-label_score, axis=0)
    found_label_str = ''
    segment_pool = zeros((size_x, size_y, no_of_labels))
    label_count  = 0
    for i in range(no_of_labels):
        if label_score[max_label_index[i]] > 4e3:
            found_label_str =  found_label_str + "%d " % (max_label_index[i]+1)
            scoredata = scoremap[:,:,1+max_label_index[i]]
            transform_scoredata = fliplr(rot90(scoredata, 3))
            for j in range(size_x):
                for k in range(size_y):
                    if transform_scoredata[j,k] >= 0.9:
                        segment_pool[j,k,label_count] = 1
            label_count = label_count + 1
        elif i == 0:
            found_label_str = '0'
            break               
    
    color = array([[255, 0, 0],
                   [0, 255, 0],
                   [0, 0, 255],
                   [255, 255,0],
                   [0, 255, 255],
                   [255, 0, 255],
                   [192, 192, 192],
                   [128, 128, 128],
                   [128, 0, 0],
                   [128, 128, 0],
                   [0, 128, 0],
                   [128, 0, 128],
                   [0, 128, 128],
                   [0, 0, 128]])
    color_size = color.shape
    n_colors = color_size[0]
    color_idx = 0
    bground = zeros((size_x, size_y, 3))
    alpha = 0.5
    I = imread(home_dir + '/examples/images/cropped/' + imgname + '.jpg')
    for i in range(label_count):
        bground[:,:,0] = color[color_idx,0].copy()
        bground[:,:,1] = color[color_idx,1].copy()
        bground[:,:,2] = color[color_idx,2].copy()
        current_segment = segment_pool[:,:,i]
        alpha_chann = current_segment * alpha
        I = immerge(I, bground, alpha_chann)
        if color_idx == n_colors - 1:
            color_idx = 0
        else:
            color_idx = color_idx + 1
    imsave('/var/www/html/segments/' + imgname + '.jpg', I)
    return found_label_str      
                                                
def immerge(bg, fg, coef):
    dif = fg - bg
    coef = concatenate((coef[..., newaxis], coef[..., newaxis], coef[..., newaxis]), axis=2)
    out = bg + coef * dif
    return out

def crop_imagefile(imgname):
    global home_dir
    I = imread('/var/www/html/upload/' + imgname + '.jpg')
    img = crop_image(I)
    imsave(home_dir + '/examples/images/cropped/' + imgname + '.jpg', img)
    
def crop_image(I):
    cropsize =321
    image_size = I.shape
    Iy = image_size[0]
    Ix = image_size[1]
    cropped_image = zeros((cropsize, cropsize, 3))
    if Ix > Iy:
        resize_percent = cropsize / Ix
        resize_Iy = int(Iy * resize_percent)
        resizeI = imresize(I,(resize_Iy, cropsize))
        shift_y = int(round((cropsize - resize_Iy) / 2))
        for i in range(cropsize):
            for j in range(resize_Iy):
                r, g, b = resizeI[j, i]
                cropped_image[j+shift_y, i] = [r, g, b]
    elif Ix < Iy:
        resize_percent = cropsize / Iy
        resize_Ix = int(Ix * resize_percent)
        resizeI = imresize(I,(cropsize, resize_Ix))
        shift_x = int(round((cropsize - resize_Ix) / 2))
        for i in range(resize_Ix):
            for j in range(cropsize):
                r, g, b = resizeI[j, i]
                cropped_image[j, i+shift_x] = [r, g, b]         
    else:
        cropped_image = imresize(I, (cropsize, cropsize))
        
    return cropped_image
          
if __name__ == '__main__':
    show_object(sys.argv[1])        
