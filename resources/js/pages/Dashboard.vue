<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle, CardFooter } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Users, Building2, Calendar, ArrowUpRight, ArrowDownRight, Plus, Bell, Settings, Search } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const stats = [
    {
        title: 'Total Users',
        value: '2,345',
        change: '+12.5%',
        trend: 'up',
        icon: Users,
        color: 'text-blue-500',
        bgColor: 'bg-blue-100 dark:bg-blue-900/20',
    },
    {
        title: 'Total Teams',
        value: '45',
        change: '+5.2%',
        trend: 'up',
        icon: Building2,
        color: 'text-green-500',
        bgColor: 'bg-green-100 dark:bg-green-900/20',
    },
    {
        title: 'Active Projects',
        value: '12',
        change: '-2.4%',
        trend: 'down',
        icon: Calendar,
        color: 'text-orange-500',
        bgColor: 'bg-orange-100 dark:bg-orange-900/20',
    },
];

const recentActivities = [
    {
        title: 'New team member joined',
        description: 'John Doe joined Development Team',
        time: '2 hours ago',
        avatar: 'https://github.com/shadcn.png',
        initials: 'JD',
    },
    {
        title: 'Project milestone completed',
        description: 'Frontend development phase completed',
        time: '5 hours ago',
        avatar: 'https://github.com/shadcn.png',
        initials: 'TS',
    },
    {
        title: 'New project created',
        description: 'E-commerce platform project started',
        time: '1 day ago',
        avatar: 'https://github.com/shadcn.png',
        initials: 'AL',
    },
];

const teamProgress = [
    {
        name: 'Development Team',
        progress: 75,
        members: 8,
        status: 'active',
    },
    {
        name: 'Design Team',
        progress: 45,
        members: 5,
        status: 'active',
    },
    {
        name: 'QA Team',
        progress: 90,
        members: 4,
        status: 'completed',
    },
];

const quickActions = [
    {
        title: 'Add New Member',
        icon: Users,
        href: '/management/members/create',
    },
    {
        title: 'Create Team',
        icon: Building2,
        href: '/management/teams/create',
    },
    {
        title: 'Schedule Meeting',
        icon: Calendar,
        href: '#',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header Actions -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <input
                            type="text"
                            placeholder="Search..."
                            class="h-10 w-[300px] rounded-md border border-input bg-background pl-9 pr-4 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        />
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="outline" size="icon">
                        <Bell class="h-4 w-4" />
                    </Button>
                    <Button variant="outline" size="icon">
                        <Settings class="h-4 w-4" />
                    </Button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid gap-4 md:grid-cols-3">
                <Card v-for="stat in stats" :key="stat.title" class="overflow-hidden">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">
                            {{ stat.title }}
                        </CardTitle>
                        <div :class="[stat.bgColor, 'rounded-full p-2']">
                            <component :is="stat.icon" :class="stat.color" class="h-4 w-4" />
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stat.value }}</div>
                        <div class="flex items-center space-x-2 text-xs">
                            <component 
                                :is="stat.trend === 'up' ? ArrowUpRight : ArrowDownRight" 
                                :class="stat.trend === 'up' ? 'text-green-500' : 'text-red-500'"
                                class="h-4 w-4"
                            />
                            <span :class="stat.trend === 'up' ? 'text-green-500' : 'text-red-500'">
                                {{ stat.change }}
                            </span>
                            <span class="text-muted-foreground">from last month</span>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Main Content -->
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Recent Activities -->
                <Card>
                    <CardHeader>
                        <CardTitle>Recent Activities</CardTitle>
                        <CardDescription>Latest updates from your team</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div v-for="activity in recentActivities" :key="activity.title" class="flex items-start space-x-4">
                                <Avatar>
                                    <AvatarImage :src="activity.avatar" :alt="activity.initials" />
                                    <AvatarFallback>{{ activity.initials }}</AvatarFallback>
                                </Avatar>
                                <div class="flex-1 space-y-1">
                                    <p class="text-sm font-medium">{{ activity.title }}</p>
                                    <p class="text-sm text-muted-foreground">{{ activity.description }}</p>
                                    <p class="text-xs text-muted-foreground">{{ activity.time }}</p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button variant="outline" class="w-full">
                            View All Activities
                        </Button>
                    </CardFooter>
                </Card>

                <!-- Team Progress -->
                <Card>
                    <CardHeader>
                        <CardTitle>Team Progress</CardTitle>
                        <CardDescription>Current project completion status</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div v-for="team in teamProgress" :key="team.name" class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium">{{ team.name }}</span>
                                        <Badge :variant="team.status === 'completed' ? 'default' : 'secondary'">
                                            {{ team.status }}
                                        </Badge>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Users class="h-4 w-4 text-muted-foreground" />
                                        <span class="text-sm text-muted-foreground">{{ team.members }} members</span>
                                    </div>
                                </div>
                                <Progress :value="team.progress" class="h-2" />
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button variant="outline" class="w-full">
                            View All Teams
                        </Button>
                    </CardFooter>
                </Card>
            </div>

            <!-- Quick Actions -->
            <Card>
                <CardHeader>
                    <CardTitle>Quick Actions</CardTitle>
                    <CardDescription>Common tasks and shortcuts</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="flex flex-wrap gap-4">
                        <Button 
                            v-for="action in quickActions" 
                            :key="action.title"
                            variant="outline" 
                            class="gap-2"
                            :href="action.href"
                        >
                            <component :is="action.icon" class="h-4 w-4" />
                            {{ action.title }}
                        </Button>
                        <Button variant="outline" class="gap-2">
                            <Plus class="h-4 w-4" />
                            More Actions
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
