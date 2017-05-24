#!/bin/sh
cd /home/ubuntu/deeplab/
export LD_PRELOAD=/usr/lib/arm-linux-gnueabihf/libstdc++.so.6

# MODIFY PATH for YOUR SETTING
NET_ID=coco_largefov
CAFFE_BIN=build/tools/caffe.bin
DEV_ID=0
TEST_ITER=1

CMD="${CAFFE_BIN} test \
     --model=models/coco_largefov/test-clock.prototxt \
     --weights=models/coco_largefov/clock_iter_200.caffemodel \
     --gpu=${DEV_ID} \
     --iterations=${TEST_ITER}"
# echo Running ${CMD} && ${CMD}
${CMD}







